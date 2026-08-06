module.exports = {
    testEnvironment: 'jsdom',
    testEnvironmentOptions: { url: 'http://127.0.0.1:38765/runner.html' },
    setupFilesAfterEnv: ['<rootDir>/scripts/jest.setup.cjs'],
    testMatch: ['<rootDir>/scripts/jest.runner.cjs'],
    testTimeout: 30000,
    maxWorkers: 1
};