export function useGenerateRepetitive () {
    const generateIndexedNames = (index, baseNames, separator = '_', concat = []) => {
        const indexedNames = baseNames.map(name => `${name}${separator}${index}`);
        return indexedNames.concat(concat);
    };

    return { generateIndexedNames };
}