const fs = require('node:fs');
const http = require('node:http');
const path = require('node:path');
const vm = require('node:vm');
const { JSDOM } = require('jsdom');
const QUnit = require('qunit');

const projectRoot = path.resolve(__dirname, '..');
const testRoot = path.join(projectRoot, 'src', 'test');
const sourceRoot = path.join(projectRoot, 'dist', 'ts');

QUnit.config.testTimeout = 30000;

function requestPath(url) {
  const parsed = new URL(url);
  const pathname = parsed.pathname;
  const resourceRoot = path.join(testRoot, 'resources');
  if (pathname.startsWith('/resources/')) {
    return path.resolve(resourceRoot, pathname.slice('/resources/'.length));
  }
  return path.resolve(testRoot, pathname.slice(1) || 'index.html');
}

function collectTests(directory) {
  return fs.readdirSync(directory, { withFileTypes: true }).flatMap((entry) => {
    const file = path.join(directory, entry.name);
    return entry.isDirectory() ? collectTests(file) : file.endsWith('.js') ? [file] : [];
  });
}

function serve(request, response) {
  const file = requestPath(`http://127.0.0.1${request.url || '/'}`);
  const allowedRoots = [testRoot, path.join(testRoot, 'resources')];
  if (!allowedRoots.some((root) => file.startsWith(root)) || !fs.existsSync(file) || !fs.statSync(file).isFile()) {
    response.writeHead(404);
    response.end('Not found');
    return;
  }
  response.writeHead(200, {
    'Access-Control-Allow-Origin': '*',
    'Access-Control-Allow-Headers': '*',
    'Access-Control-Allow-Methods': 'GET, OPTIONS'
  });
  fs.createReadStream(file).pipe(response);
}

const server = http.createServer(serve);
server.listen(0, '127.0.0.1', () => {
  const port = server.address().port;
  const testOrigin = `http://127.0.0.1:${port}`;
  const dom = new JSDOM('<!doctype html><html><body></body></html>', {
    url: `${testOrigin}/runner.html`,
    pretendToBeVisual: true
  });

  dom.window.XMLHttpRequest = class LocalXMLHttpRequest extends dom.window.XMLHttpRequest {
    open(method, url, ...args) {
      const parsed = new URL(url, `${testOrigin}/`);
      return super.open(method, parsed.href, ...args);
    }
  };

  global.window = dom.window;
  global.document = dom.window.document;
  global.navigator = dom.window.navigator;
  global.location = dom.window.location;
  global.XMLHttpRequest = dom.window.XMLHttpRequest;
  global.QUnit = QUnit;
  global.org_turbocommons = require(sourceRoot);
  global.window.org_turbocommons = global.org_turbocommons;

  QUnit.config.autostart = false;
  QUnit.on('runEnd', (details) => {
    server.close();
    dom.window.close();
    process.exitCode = details.testCounts.failed > 0 ? 1 : 0;
    console.log(`TypeScript tests: ${details.testCounts.total} total, ${details.testCounts.passed} passed, ${details.testCounts.failed} failed`);
  });

  const testContext = vm.createContext(dom.window);
  Object.assign(testContext, {
    QUnit,
    XMLHttpRequest: dom.window.XMLHttpRequest,
    org_turbocommons: global.org_turbocommons,
    console,
    setTimeout,
    clearTimeout
  });

  for (const testFile of collectTests(path.join(testRoot, 'js'))) {
    vm.runInContext(fs.readFileSync(testFile, 'utf8'), testContext, { filename: testFile });
  }
  QUnit.start();
});
