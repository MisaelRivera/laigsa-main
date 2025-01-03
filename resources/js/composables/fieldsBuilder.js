import { TextElement } from "@vueform/vueform";

export function useBuild () {
    const buildTabs = (tabs) => {
        const tabsArr = [];
        for (let i = 0; i < tabs.length; i++) {
            const tab = {};
            tab['name'] = tabs[i].name;
            tab['fields'] = tab.fields.map((index, field) => {
                if (typeof field === 'string') {
                    return {
                        name: field,
                        component: 'TextElement',
                        columns: 12,
                        before: () => `<div class="text-sm">${field.name.split('_').join(' ').replace(/^./, str => str.toUpperCase())}</div>`
                    }
                } else if (typeof field === 'object') {
                    const fieldElement = {};
                    fieldElement['name'] = field.name;
                    if (Object.keys(field).includes('component')) {
                        fieldElement['component'] = field['component'];
                        if (field.component === 'SelectElement') {
                            fieldElement['options'] = field['options'];
                        }
                    } else {
                        fieldElement['component'] = 'TextElement';
                    }

                    if (Object.keys(field).includes('columns')) {
                        fieldElement['columns'] = field['columns'];
                    } else {
                        fieldElement['columns'] = 12;
                    }

                    fieldElement['before'] = () => `<div class="text-sm">${field.name.split('_').join(' ').replace(/^./, str => str.toUpperCase())}</div>`;
                }
            });
        }
    };
} 