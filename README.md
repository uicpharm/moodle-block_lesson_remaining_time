# Moodle Plugin: Lesson Remaining Time

This public Moodle plugin is a simple block that can be added only to lesson pages to show
the time remaining before the user has met the time requirement of a lesson.

Check out the [user documentation](https://remainingtime.docs.uicpharm.dev). For technical
developer instructions, keep reading.

## Installation

The build tooling for this plugin uses Node.js. Use
[nvm](https://github.com/nvm-sh/nvm#readme) to install the right version of Node, and then
run `npm install`.

```bash
nvm use
npm install
```

## Building

To build the project, run `npm run build`, or use `npm run build:docs` or
`npm run build:plugin` to build just one or the other.

## Development

You can run `npm run dev` while actively working on code or docs (Use`npm run dev:docs`
or `npm run dev:plugin` to explicitly target just one or the other).

To send the files directly to your Moodle instance, you can use `DEST` to specify where to
send them. For example, if your Moodle environment is installed at `~/proj/moodle/src`,
you could run:

```bash
DEST=~/proj/moodle/src npm run dev:plugin
```

## Version management and new releases

Don't forget to change the version number as indicated in `version.php`. Any new release
will need a new version number, which is typically a timestamp version in the format of
`YYYYMMDDHH`, so if your changes were made on June 23, 2025 at 3pm, you may use the
version `2025062315`.

Once you merge your changes into `main`, create and push a tag with that same version
number. The creation of the tag will kick off the publishing of the package on the GitHub
repo's
[releases](https://github.com/uicpharm/moodle-block_lesson_remaining_time/releases) page.

The `package.json` version number is not used since Moodle projects use the timestamp
version.
