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

    try {
        
    
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
                <form onSubmit={getsucursalDetallesData} className="input-group">
                    
                    <input type="text" placeholder="Buscar..." value={filtronominaq} onChange={e=>setfiltronominaq(e.target.value)} className="form-control" />
                    <button className="btn btn-success"><i className="fa fa-search"></i></button>
                </form>
                <table className="table">
                    <thead>
                        <tr>
                            <th>SUCURSAL</th>
                            <th>CARGO</th>
                            <th>NOMBRE</th>
                            <th>CEDULA</th>
                            <th>FECHA DE NACIMIENTO</th>
                            <th>FECHA DE INGRESO</th>
                            <th>TELEFONO</th>
                            <th>DIRECCION</th>
                            <th>GRADO INSTRUCCION</th>
                        </tr>
                    </thead>
                    <tbody>
                        {sucursalDetallesData ? sucursalDetallesData.length?
                            sucursalDetallesData.map(e=><tr key={e.id}>
                                <td>{e.sucursal.codigo}</td>
                                <td>{cargosDataFun(e.nominacargo)}</td>
                                <td>{e.nominanombre}</td>
                                <td>{e.nominacedula}</td>
                                <td>{e.nominafechadenacimiento}</td>
                                <td>{e.nominafechadeingreso}</td>
                                <td>{e.nominatelefono}</td>
                                <td>{e.nominadireccion}</td>
                                <td>{e.nominagradoinstruccion}</td>
                            </tr>)
                        :null:null}
                    </tbody>
                </table>

            </div>
        )

    } catch (error) {
        return "CARGANDO"
    }
}