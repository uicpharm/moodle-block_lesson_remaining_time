import { defineConfig } from 'vitepress'; // eslint-disable-line import/no-extraneous-dependencies
import { viteStaticCopy } from 'vite-plugin-static-copy';

export default defineConfig({
   title: 'Moodle Plugin: Lesson Remaining Time',
   description: 'Documentation for the lesson Remaining Time plugin.',
   ignoreDeadLinks: 'localhostLinks',
   outDir: '../dist/docs',
   vite: {
      ssr: {
         noExternal: [ '@uicpharm/vitepress-theme' ],
      },
      plugins: [
         viteStaticCopy({
            targets: [ { src: '../node_modules/@uicpharm/vitepress-theme/public/uic-logo.svg', dest: '.' } ],
         }),
      ],
   },
   themeConfig: {
      logo: '/uic-logo.svg',
      outline: 'deep',
      nav: [
         { text: 'Download', link: 'https://github.com/uicpharm/moodle-block_lesson_remaining_time/releases' },
      ],
      socialLinks: [
         { icon: 'github', link: 'https://github.com/uicpharm/moodle-block_lesson_remaining_time' },
      ],
   },
});
