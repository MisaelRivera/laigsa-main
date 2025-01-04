import { TextElement } from "@vueform/vueform";

export function useBuild() {
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
                    const fieldKeys = Object.keys(field);
                    if (fieldKeys.includes('component')) {
                        fieldElement['component'] = field['component'];
                        if (field.component === 'SelectElement' || field.component === 'RadioGroupElement') {
                            fieldElement['items'] = field['items'];
                        }
                    } else {
                        fieldElement['component'] = 'TextElement';
                    }

                    if (fieldKeys.includes('columns')) {
                        fieldElement['columns'] = field['columns'];
                    } else {
                        fieldElement['columns'] = 12;
                    }

                    if (fieldKeys.includes('default')) {
                        fieldElement['default'] = field.default;
                    }

                    if (fieldKeys.includes('remove-class')) {
                        fieldElement['remove-class'] = field['remove-class'];
                    }

                    if (fieldKeys.includes('add-class')) {
                        fieldElement['add-class'] = field['add-class'];
                    }


                    fieldElement['before'] = () => `<div class="text-sm">${field.name.split('_').join(' ').replace(/^./, str => str.toUpperCase())}</div>`;
                }
            });
        }
    };
} 