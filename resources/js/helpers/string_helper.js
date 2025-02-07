export function extractStringPart (string, start, end, firstStartOcurrence = true, firstEndOcurrence = true)
{
    let firstDataPos = firstStartOcurrence ? string.indexOf(start) + 1:string.lastIndexOf(start) + 1;
    let lastDataPos = firstEndOcurrence ? string.indexOf(end):string.lastIndexOf(end);
    let extractedPart = '';
    if (end === '') {
        const len = string.length;
        while(firstDataPos < len) {
            extractedPart += string[firstDataPos];
            firstDataPos++;
        }
    } else {
        while(string[firstDataPos] !== string[lastDataPos]) {
            extractedPart += string[firstDataPos];
            firstDataPos++;
        }
    }
    return extractedPart;
}

export function implodeCoordinates (grados, minutos, segundos, orientacion)
{
    return `${grados}°${minutos}'${segundos}" ${orientacion}`;
}

export function explodeCoordinates (coordinates)
{
    const grados = coordinates.split('°')[0],
          minutos = extractStringPart(coordinates, '°', "'"),
          segundos = extractStringPart(coordinates, "'", "'", true, false),
          orientacion = extractStringPart(coordinates, "'", '', false, true).trim();
    
    return {
        grados,
        minutos,
        segundos,
        orientacion
    };
}