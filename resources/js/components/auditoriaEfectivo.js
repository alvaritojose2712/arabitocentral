import FechasMain from './panel/fechasmain'
import Controldeefectivo from './panel/Controldeefectivo'
import { useEffect } from "react";


export default function AuditoriaEfectivo({
    fechasMain1,
    fechasMain2,
    setfechasMain1,
    setfechasMain2,
    controlefecQDescripcion,
    setcontrolefecQDescripcion,
    controlefecSelectCat,
    setcontrolefecSelectCat,
    getsucursalDetallesData,
    sucursalDetallesData,
    controlefecSelectGeneral,
    setcontrolefecSelectGeneral,
    moneda,
    colorsGastosCat,
    getCatCajas,
    subviewpanelsucursales,
    sucursalSelect,
    qestatusaprobaciocaja,
}){

    useEffect(()=>{
        getsucursalDetallesData(null, "controldeefectivo")
    },[
        subviewpanelsucursales,
        fechasMain1,
        fechasMain2,
        sucursalSelect,
        qestatusaprobaciocaja,
    ])

    return  <>
        <FechasMain
            fechasMain1={fechasMain1}
            fechasMain2={fechasMain2}
            setfechasMain1={setfechasMain1}
            setfechasMain2={setfechasMain2}
        />
        <Controldeefectivo
            controlefecQDescripcion={controlefecQDescripcion}
            setcontrolefecQDescripcion={setcontrolefecQDescripcion}
            controlefecSelectCat={controlefecSelectCat}
            setcontrolefecSelectCat={setcontrolefecSelectCat}
            getsucursalDetallesData={getsucursalDetallesData}
            sucursalDetallesData={sucursalDetallesData}
            controlefecSelectGeneral={controlefecSelectGeneral}
            setcontrolefecSelectGeneral={setcontrolefecSelectGeneral}
            moneda={moneda}
            colorsGastosCat={colorsGastosCat}
            getCatCajas={getCatCajas}
        
        />
    </>

}