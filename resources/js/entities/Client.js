import axios from "axios";

class Client {
    constructor (id, cliente, direccionFiscal, direccionMuestreo, observaciones, correoElectronico, telefono, encargado, cesavedac, cuartoTransitorio, identificacionesMuestra) {
        this.id = id;
        this.cliente = cliente;
        if (direccionFiscal) {
            this.direccionFiscal = direccionFiscal;
        } else {
            this.direccionFiscal = '';
        }

        if (direccionFiscal) {
            this.direccionFiscal = direccionFiscal;
        } else {
            this.direccionFiscal = '';
        }

        if (direccionMuestreo) {
            this.direccionMuestreo = direccionMuestreo;
        } else {
            this.direccionMuestreo = '';
        }

        if (observaciones) {
            this.observaciones = observaciones;
        } else {
            this.observaciones = '';
        }

        if (correoElectronico) {
            this.correoElectronico = correoElectronico;
        } else {
            this.correoElectronico = '';
        }

        if (telefono) {
            this.telefono = telefono;
        } else {
            this.telefono = '';
        }

        if (encargado) {
            this.encargado = encargado;
        } else {
            this.encargado = '';
        }

        if (cesavedac) {
            this.cesavedac = cesavedac;
        } else {
            this.cesavedac = 0;
        }

        if (cuartoTransitorio) {
            this.cuartoTransitorio = cuartoTransitorio;
        } else {
            this.cuartoTransitorio = 0;
        }

        if (identificacionesMuestra) {
            this.identificacionesMuestra = identificacionesMuestra;
        } else {
            this.identificacionesMuestra = [];
        }
    }

    static wrapClient (client) {
        return new Client(
            client.id,
            client.cliente,
            client.direccionFiscal,
            client.direccionMuestreo,
            client.observaciones,
            client.correoElectronico,
            client.telefono,
            client.encargado,
            client.cesavedac,
            client.cuartoTransitorio,
            client.identificacionesMuestra,
        );
    }

    static newClient (id, cliente, direccionFiscal, direccionMuestreo, observaciones, correoElectronico, telefono, encargado, cesavedac, cuartoTransitorio, identificacionesMuestra) {
        return new Client(
            id,
            cliente,
            direccionFiscal,
            direccionMuestreo,
            observaciones,
            correoElectronico,
            telefono,
            encargado,
            cesavedac,
            cuartoTransitorio,
            identificacionesMuestra
        );
    }

    async getSampleIdentification () {
        if (!this.identificacionesMuestra.length) {
            this.identificacionesMuestra = await axios.get(`/get_by_client_id/${this.id}`);
        }

        return this.identificacionesMuestra.data;
    }
}

export default Client;