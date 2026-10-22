export default function (data, maquila) {
    maquila.empaques = data[22];
    maquila.entregas = data[23];
    maquila.lugarEntregas = data[13];
    maquila.suajadosList = data[37];
}