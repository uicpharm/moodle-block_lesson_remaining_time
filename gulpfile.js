/* eslint no-param-reassign: ['error', { props: false }] */
import fs from 'fs';
import path from 'path';
import cleanCSS from 'gulp-clean-css';
import gulp from 'gulp';
import merge from 'merge-stream';
import rename from 'gulp-rename';
import replace from 'gulp-replace';
import terser from 'gulp-terser';
import zip from 'gulp-zip';

const base = 'src';
const cssGlobs = [ `${base}/**/*.css` ];
const jsGlobs = [ `${base}/**/*.js` ];
const phpGlobs = [ `${base}/**/*.php` ];

const { name: packageName } = JSON.parse(fs.readFileSync('./package.json', 'utf8'));
const frankenstyleName = packageName.replace(/^moodle-/, '');

function getPluginVersion() {
   const versionFile = fs.readFileSync('src/version.php', 'utf8');
   const match = versionFile.match(/^\s*\$plugin->version\s*=\s*(\d+);/m);
   if (!match) throw new Error('version.php does not contain a valid $plugin->version.');
   return match[1];
}

function prefixPath(prefix) {
   return rename((filePath) => {
      filePath.dirname = path.join(prefix, filePath.dirname);
   });
}

const jsStream = () => gulp.src(jsGlobs, { base }).pipe(terser());
const cssStream = () => gulp.src(cssGlobs, { base }).pipe(cleanCSS());
const phpStream = () => gulp.src(phpGlobs, { base }).pipe(replace(/%%version%%/gi, getPluginVersion()));

export function pkg() {
   return merge(jsStream(), cssStream(), phpStream())
      .pipe(prefixPath(frankenstyleName))
      .pipe(zip(`${frankenstyleName}_${getPluginVersion()}.zip`))
      .pipe(gulp.dest('dist/package'));
}

export function send() {
   const defaultDest = 'dist/dev';
   const dest = process.env.DEST ?? defaultDest;

   if (!process.env.DEST) {
      console.warn('To send the package to your Moodle instance, set the DEST environment variable. Example: DEST=/my/path');
   }

   return merge(jsStream(), cssStream(), phpStream())
      // If frankenstyle is local_foo, we want it to become local/foo
      // And frankenstyle "block" goes to the "blocks" directory
      .pipe(prefixPath(frankenstyleName.replace('_', '/').replace('block', 'blocks')))
      .pipe(gulp.dest(dest));
}

export function watch() {
   gulp.watch([ cssGlobs, jsGlobs, phpGlobs ], send);
}

export const build = gulp.parallel(pkg);
export default gulp.series(send, watch);
