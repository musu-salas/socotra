'use strict';

const fs = require('fs');
const path = require('path');
const readdir = require('recursive-readdir');

function readFile(filename) {
  return new Promise((resolve, reject) => {
    fs.readFile(filename, 'utf8', (error, contents) => {
      if (error) {
        reject(error);
        return;
      }

      resolve(contents);
    });
  });
}

function writeFile(filename, contents) {
  return new Promise((resolve, reject) => {
    fs.writeFile(filename, contents, (error, contents) => {
      if (error) {
        reject(error);
        return;
      }

      resolve(filename);
    });
  });
}

function findMatches(contents) {
  const matches = [];
  let match = PATTERN.exec(contents);

  while (match) {
    matches.push(match[1]);
    contents = contents.substr(match.index + match[0].length)
    match = PATTERN.exec(contents);
  }

  return matches;
}

function extractMatches(filename) {
  return readFile(filename)
    .then(findMatches);
}

function writeTranslations(matches) {
  const translations = {};

  for (const match of matches) {
    translations[match] = '';
  }

  console.info(`Pattern matches: ${matches.length}, translations: ${Object.keys(translations).length}`);

  return writeFile(OUT_DIR, JSON.stringify(translations, null, 2));
}

const OUT_DIR = path.join(__dirname, 'resources/lang/en.json');
const PATTERN = /_(?:_|n)\(\'(.*?)\'(?:\)|,)/;

Promise
  .all([
    readdir(path.join(__dirname, 'resources/views')),
    readdir(path.join(__dirname, 'app'))
  ])
  .then((results) => [].concat(...results)/* .slice(0, 1) */)
  .then((filenames) => Promise.all(filenames.map(extractMatches)))
  .then((results) => [].concat(...results))
  .then(writeTranslations)
  .then((outFilename) => {
    console.info(`Translation strings extracted to ${outFilename}`);
    process.exit();
  })
  .catch((ex) => {
    console.trace(ex);
    process.exit(1);
  });
