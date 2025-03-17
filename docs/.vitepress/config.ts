import { defineConfig } from 'vitepress'
import { withMermaid } from "vitepress-plugin-mermaid";

// https://vitepress.dev/reference/site-config
export default withMermaid(
    defineConfig({
        base: '/docs/',
        outDir: '../public/docs',
        title: "VL. WBK docs",
        description: "Onze bijhorende documentatie voor de ontwikkeling en gebruik van het Vl woordenboek ",
        themeConfig: {
            // https://vitepress.dev/reference/default-theme-config
            nav: [
                { text: 'Code of Conduct', link: 'https://github.com/Tjoosten/vl-woordenboek/blob/develop/CODE_OF_CONDUCT.md', 'target': '_blank' },
                { text: 'LICENSE', link: 'https://github.com/Tjoosten/vl-woordenboek/blob/develop/LICENSE', 'target': '_blank' },
                { text: 'Contributing', link: '/' },
            ],
            sidebar: [
                {
                    text: 'Artikelen',
                    collapsed: true,
                    items: [
                        { text: 'Levenscyclus', link: "/dictionary-articles/lifecycle" },
                        { text: 'Notities', link: '/dictionary-articles/notes' },
                        { text: 'Labels', link: '/dictionary-articles/labels'},
                        { text: 'Versiebeheer', link: '/dictionary-articles/versiebeheer' }
                    ]
                },
                {
                    text: 'Overig',
                    collapsed: true,
                    items: [
                        { text: 'Glossarium', link: '/other/glossarium' },
                    ]
                }
            ],

            socialLinks: [
                { icon: 'github', link: 'https://github.com/vuejs/vitepress' },
            ]
        }
    })
)
