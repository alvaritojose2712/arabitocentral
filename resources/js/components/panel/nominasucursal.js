import { useEffect } from "react"

export default function NominasSucursal({
    
    getsucursalDetallesData,
    sucursalDetallesData,
    controlefecSelectGeneral,
    setcontrolefecSelectGeneral,
    filtronominaq,
    setfiltronominaq,
    filtronominacargo,
    setfiltronominacargo,
    moneda,
    cargosData,
    getPersonalCargos

}){
   useEffect(()=>{
    getPersonalCargos()

   },[])

   const cargosDataFun = val => {
    let m = cargosData.filter(e=>e.id==val)

    if (m.length) {
        return m[0].cargosdescripcion;
    }
    return "ERROR";
   }

    return (
        <div className="container-fluid">
            <div className="input-group">
                <input type="text" placeholder="Buscar..." value={filtronominaq} onChange={e=>setfiltronominaq(e.target.value)} className="form-control" />
            </div>
            <table className="table">
                <thead>
                    <tr>
                        <td>NOMBRE</td>
                        <td>CEDULA</td>
                        <td>FECHA DE NACIMIENTO</td>
                        <td>FECHA DE INGRESO</td>
                        <td>TELEFONO</td>
                        <td>DIRECCION</td>
                        <td>GRADO INSTRUCCION</td>
                        <td>CARGO</td>
                        <td>SUCURSAL</td>
                    </tr>
                </thead>
                <tbody>
                    {sucursalDetallesData ? sucursalDetallesData.length?
                        sucursalDetallesData.map(e=><tr key={e.id}>
                            <td>{e.nominanombre}</td>
                            <td>{e.nominacedula}</td>
                            <td>{e.nominafechadenacimiento}</td>
                            <td>{e.nominafechadeingreso}</td>
                            <td>{e.nominatelefono}</td>
                            <td>{e.nominadireccion}</td>
                            <td>{e.nominagradoinstruccion}</td>
                            <td>{cargosDataFun(e.nominacargo)}</td>
                            <td>{e.nominasucursal}</td>
                        </tr>)
                    :null:null}
                </tbody>
            </table>

        </div>
    )
}