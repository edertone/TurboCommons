const fs = require('node:fs');
const http = require('node:http');
const path = require('node:path');

const projectRoot = path.resolve(__dirname, '..');
const testRoot = path.join(projectRoot, 'tests');
const sourceRoot = path.join(projectRoot, 'dist', 'ts');
const resourceRoot = path.join(testRoot, 'resources');

function requestPath(url) {
    const parsed = new URL(url);
    const pathname = parsed.pathname;
    if (pathname.startsWith('/resources/')) {
        return path.resolve(resourceRoot, pathname.slice('/resources/'.length));
    }
    return path.resolve(testRoot, pathname.slice(1) || 'index.html');
}

const server = http.createServer((request, response) => {
    const file = requestPath(`http://127.0.0.1${request.url || '/'}`);
    const allowedRoots = [testRoot, resourceRoot];
    if (!allowedRoots.some((root) => file.startsWith(root)) || !fs.existsSync(file) || !fs.statSync(file).isFile()) {
        response.writeHead(404);
        response.end('Not found');
        return;
    }
    response.writeHead(200, {
        'Access-Control-Allow-Origin': '*',
        'Access-Control-Allow-Headers': '*',
        'Access-Control-Allow-Methods': 'GET, POST, OPTIONS'
    });
    fs.createReadStream(file).pipe(response);
});

let serverClosed = false;
const testOrigin = 'http://127.0.0.1:38765';
server.listen(38765, '127.0.0.1');

beforeAll((done) => {
    const nativeXMLHttpRequest = window.XMLHttpRequest;
    window.XMLHttpRequest = class LocalXMLHttpRequest extends nativeXMLHttpRequest {
        open(method, url, ...args) {
            const parsed = new URL(url, `${testOrigin}/`);
            return super.open(method, parsed.href, ...args);
        }
    };
    global.XMLHttpRequest = window.XMLHttpRequest;
    done();
});

afterAll((done) => {
    if (serverClosed) {
        done();
        return;
    }
    serverClosed = true;
    server.close(() => done());
});

global.org_turbocommons = require(sourceRoot);
window.org_turbocommons = global.org_turbocommons;

function execute(callback, done) {
    let pending = 0;
    let completed = false;

    const complete = (error) => {
        if (completed) {
            return;
        }
        completed = true;
        done(error);
    };

    const assert = {
        ok(value, message) {
            try {
                expect(value).toBeTruthy();
            } catch (error) {
                if (pending > 0) {
                    complete(error);
                    return;
                }
                throw error;
            }
        },
        notOk(value, message) {
            try {
                expect(value).toBeFalsy();
            } catch (error) {
                if (pending > 0) {
                    complete(error);
                    return;
                }
                throw error;
            }
        },
        strictEqual(actual, expected, message) {
            try {
                expect(actual).toBe(expected);
            } catch (error) {
                if (pending > 0) {
                    complete(error);
                    return;
                }
                throw error;
            }
        },
        notStrictEqual(actual, expected, message) {
            try {
                expect(actual).not.toBe(expected);
            } catch (error) {
                if (pending > 0) {
                    complete(error);
                    return;
                }
                throw error;
            }
        },
        throws(callbackToTest, expected, message) {
            try {
                expect(callbackToTest).toThrow(expected);
            } catch (error) {
                if (pending > 0) {
                    complete(error);
                    return;
                }
                throw error;
            }
        },
        async(count = 1) {
            pending += count;
            return () => {
                pending -= 1;
                if (pending === 0) {
                    complete();
                }
            };
        }
    };

    try {
        const result = callback(assert);
        if (result && typeof result.then === 'function') {
            result.then(() => complete(), complete);
        } else if (pending === 0) {
            complete();
        }
    } catch (error) {
        complete(error);
    }
}

const modules = [];
let currentModule;

global.QUnit = {
    module(name, hooks = {}) {
        currentModule = { name, hooks, tests: [] };
        modules.push(currentModule);
    },
    test(name, callback) {
        currentModule.tests.push({ name, callback, todo: false });
    },
    todo(name, callback) {
        currentModule.tests.push({ name, callback, todo: true });
    }
};
window.QUnit = global.QUnit;

window.QUnit = global.QUnit;

function collectTests(directory) {
    return fs.readdirSync(directory, { withFileTypes: true }).flatMap((entry) => {
        const file = path.join(directory, entry.name);
        return entry.isDirectory() ? collectTests(file) : file.endsWith('.js') ? [file] : [];
    });
}

for (const testFile of collectTests(path.join(testRoot, 'js'))) {
    const source = fs.readFileSync(testFile, 'utf8').replace(/^["']use strict["'];\s*/, '');
    const runTestFile = new Function('window', `with (window) {\n${source}\n}`);
    runTestFile(window);
}

for (const current of modules) {
    describe(current.name, () => {
        if (current.hooks.before) {
            beforeAll((done) => execute(current.hooks.before, done));
        }
        if (current.hooks.beforeEach) {
            beforeEach((done) => execute(current.hooks.beforeEach, done));
        }
        if (current.hooks.afterEach) {
            afterEach((done) => execute(current.hooks.afterEach, done));
        }
        if (current.hooks.after) {
            afterAll((done) => execute(current.hooks.after, done));
        }
        for (const registeredTest of current.tests) {
            if (registeredTest.todo) {
                test.todo(registeredTest.name);
            } else {
                test(registeredTest.name, (done) => execute(registeredTest.callback, done));
            }
        }
    });
}