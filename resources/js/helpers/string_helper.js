export function implodeCoordinates (grados, minutos, segundos, orientacion)
{
    return `${grados}°${minutos}'${segundos}" ${orientacion}`;
}

export function explodeCoordinates (coordinates)
{
    const split1 = coordinates.split('°');
    console.log(coordinates);
    const grados = split1[0];
    const split2 = split1[1].split("'");
    console.log(split2);
    const minutos = split2[0];
    const split3 = split2[1].split('"');
    const segundos = split3[0];
    const orientacion = split3[1].trim();
    return {
        grados,
        minutos,
        segundos,
        orientacion
    };
}