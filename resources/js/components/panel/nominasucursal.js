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
            <div className="container-fluid p-0">
                <form onSubmit={getsucursalDetallesData} className="input-group">
                    
                    <input type="text" placeholder="Buscar..." value={filtronominaq} onChange={e=>setfiltronominaq(e.target.value)} className="form-control" />
                    <button className="btn btn-success"><i className="fa fa-search"></i></button>
                </form>

                    
                <table className="table">
                    <thead>
                        <tr>
                        <th className="bg-warning fs-3 text-center">
                            {sucursalDetallesData?

                                typeof sucursalDetallesData.sum=="number"?
                                sucursalDetallesData.sum
                                :null
                            :null}
                        </th>
                        <th></th>

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
                    
                        {sucursalDetallesData.data?Object.entries(sucursalDetallesData.data).length?
                            Object.entries(sucursalDetallesData.data).map(e=>
                                <tbody key={e[0]}>
                                    <tr>
                                        <th>{e[0]}</th>          
                                    </tr>
                                    {
                                        Object.entries(e[1]).map(ee=>
                                            <>
                                                <tr key={ee[0]}>
                                                    <th></th>
                                                    <th>{ee[0]}</th>          
                                                </tr>
                                                {
                                                    ee[1].map(eee=>
                                                        <tr key={eee.id}>
                                                            <th></th>
                                                            <th></th>


                                                            <td>{eee.sucursal.codigo}</td>
                                                            <td>{cargosDataFun(eee.nominacargo)}</td>
                                                            <td>{eee.nominanombre}</td>
                                                            <td>{eee.nominacedula}</td>
                                                            <td>{eee.nominafechadenacimiento}</td>
                                                            <td>{eee.nominafechadeingreso}</td>
                                                            <td>{eee.nominatelefono}</td>
                                                            <td>{eee.nominadireccion}</td>
                                                            <td>{eee.nominagradoinstruccion}</td>        
                                                        </tr>
                                                    )
                                                }
                                            
                                            </>
                                        )
                                    }
                                </tbody>

                            )
                        :null:null}
                </table>

                {/* <td>{e.sucursal.codigo}</td>
                <td>{cargosDataFun(e.nominacargo)}</td>
                <td>{e.nominanombre}</td>
                <td>{e.nominacedula}</td>
                <td>{e.nominafechadenacimiento}</td>
                <td>{e.nominafechadeingreso}</td>
                <td>{e.nominatelefono}</td>
                <td>{e.nominadireccion}</td>
                <td>{e.nominagradoinstruccion}</td> */}

            </div>
        )

    } catch (error) {
        return "CARGANDO"
    }
}