const fs = require('fs');
const path = require('path');
const replace = require('replace-in-file');
const output = require('./output');

// Helpers
const has = Object.prototype.hasOwnProperty;

// Functions
const consoleOutput = (color, text) => {
  console.log(color, text);
};

exports.rootDir = path.join(__dirname, '..');
exports.manifest = path.join(`${exports.rootDir}/theme-manifest.json`);
exports.wpContentFolder = path.join(`${exports.rootDir}/wp-content`);
exports.themeFolder = path.join(`${exports.wpContentFolder}/themes`);

/**
 * Reads and returns the contents of theme-manifest.json
 */
exports.readManifestFull = () => {
  let oldManifest = '';
  if (fs.existsSync(exports.manifest)) {
    oldManifest = JSON.parse(fs.readFileSync(exports.manifest, 'utf8'));
  }
  return oldManifest;
};

/**
 * Reads and returns a specific key from theme-manifest.json
 */
exports.readManifest = (key) => {
  if (!fs.existsSync(exports.manifest)) {
    throw new Error('Unable to find the manifest file!');
  }

  const manifest = JSON.parse(fs.readFileSync(exports.manifest, 'utf8'));

  if (!has.call(manifest, key)) {
    throw new Error(`Unable to find value for: ${key}`);
  }

  return manifest[key];
};

exports.findReplace = async(findString, replaceString) => {
  const regex = new RegExp(findString, 'g');
  const options = {
    files: `${exports.rootDir}/**/*`,
    from: regex,
    to: replaceString,
    ignore: [
      path.join(`${exports.rootDir}/node_modules/**/*`),
      path.join(`${exports.rootDir}/.git/**/*`),
      path.join(`${exports.rootDir}/.github/**/*`),
      path.join(`${exports.rootDir}/vendor/**/*`),
      path.join(`${exports.rootDir}/wp-admin/**/*`),
      path.join(`${exports.rootDir}/wp-includes/**/*`),
      path.join(`${exports.rootDir}/bin/rename.js`),
      path.join(`${exports.rootDir}/bin/rename-runnable.js`),
      path.join(`${exports.rootDir}/bin/setup.js`),
      path.join(`${exports.rootDir}/bin/setup-wp.js`),
      path.join(`${exports.rootDir}/bin/output.js`),
      path.join(`${exports.rootDir}/bin/files.js`),
      path.join(`${exports.rootDir}/theme-manifest.js`),
    ],
  };

  if (findString !== replaceString) {
    await replace(options);
  }
};

exports.renameAllFiles = async(oldManifest, newManifest, skipSinces) => {
  await exports.findReplace(oldManifest.name, newManifest.name);
  await exports.findReplace(oldManifest.description, newManifest.description);
  await exports.findReplace(oldManifest.author, newManifest.author);
  await exports.findReplace(oldManifest.package, newManifest.package);
  await exports.findReplace(oldManifest.namespace, newManifest.namespace);
  await exports.findReplace(oldManifest.env, newManifest.env);
  await exports.findReplace(oldManifest.assetManifest, newManifest.assetManifest);
  await exports.findReplace(oldManifest.url, newManifest.url);

  // Rename sinces
  if (!skipSinces) {
    await exports.findReplace('@since.*\n', '@since 1.0.0\n');
    await exports.findReplace('@since 1.0.0\n.*@since 1.0.0\n', '@since 1.0.0\n');
  }
};

