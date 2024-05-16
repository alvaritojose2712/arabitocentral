import { useState, useEffect } from "react";
export default function Alquileres({
    alquileresData,
    alquileresq,
    setalquileresq,
    alquileresq_sucursal,
    setalquileresq_sucursal,
    getAlquileres,
    sucursales,

    sendalquilerdesc,
    setsendalquilerdesc,
    sendalquilermonto,
    setsendalquilermonto,
    sendalquilersucursal,
    setsendalquilersucursal,

    setNewAlquiler,

    sendalquilerid,
    setsendalquilerid,
    subviewAlquileres,
    setsubviewAlquileres,
    colorSucursal,
    delAlquiler,
    moneda,
}){
    useEffect(()=>{
        getAlquileres()
    },[])
    const modeEdit = (id) => {
        setsubviewAlquileres("cargar")
        setsendalquilerid(id)
        let fil = alquileresData.data.filter(e=>e.id===id)
        if (fil.lenght) {
            setsendalquilerdesc(fil[0].descripcion)
            setsendalquilermonto(fil[0].monto)
            setsendalquilersucursal(fil[0].id_sucursal)
        }
    }
    return (
        <div className="container">
            <div className="text-center">
                <div className="btn-group m-3">
                    <button className={("fs-3 btn btn")+(subviewAlquileres=="list"?"":"-outline")+("-sinapsis")} onClick={()=>setsubviewAlquileres("list")}>LISTA <i className="fa fa-paper-plane"></i> </button>
                    <button className={("fs-3 btn btn")+(subviewAlquileres=="cargar"?"":"-outline")+("-sinapsis")} onClick={()=>setsubviewAlquileres("cargar")}>CARGAR <i className="fa fa-save"></i></button>
                </div>

            </div>
            {subviewAlquileres==="list"?
                <>
                    <form className="form-group" onSubmit={event=>{event.preventDefault();getAlquileres()}}>
                        <div className="input-group">
                            <input type="text" className="form-control" value={alquileresq} onChange={e=>setalquileresq(e.target.value)} placeholder="Buscar por descripción..." />
                            <select className="form-control form-control-lg" value={alquileresq_sucursal} onChange={e=>setalquileresq_sucursal(e.target.value)}>
                                <option value="">-SUCURSAL-</option>
                                {sucursales.map(e=>
                                    <option key={e.id} value={e.id}>{e.codigo}</option>
                                )}
                            </select>
                            <button type="submit" className="btn btn-success btn-lg"><i className="fa fa-search"></i></button>
                        </div>
                    </form>
                    <table className="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>DESCRIPCIÓN</th>
                                <th>MONTO</th>
                                <th className="text-center">SUCURSAL</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            {alquileresData.data?alquileresData.data.map(e=>
                                <tr key={e.id}>
                                    <td>{e.id}</td>
                                    <td>{e.descripcion}</td>
                                    <td>{moneda(e.monto)}</td>
                                    <td>
                                        {e.sucursal?
                                            <button className={"btn w-100 fw-bolder fs-4"} style={{backgroundColor:colorSucursal(e.sucursal.codigo)}}>
                                                {e.sucursal.codigo}
                                            </button>
                                        :null}

                                    </td>
                                    <td> <button className="btn btn-sinapsis" type="button" onClick={()=>modeEdit(e.id)}> <i className="fa fa-pencil"></i> </button> </td>
                                </tr>
                            ):null}
                        </tbody>
                    </table>
                </>
            :null}

            {subviewAlquileres==="cargar"?
                <>
                    {sendalquilerid?
                        <h1>EDITANDO <i onClick={()=>delAlquiler()} className="text-danger fa-2x fa fa-times"></i></h1>
                        :
                        <h1>GUARDAR <i className="fa fa-save"></i></h1>
                    }
                    <form onSubmit={event=>{event.preventDefault();setNewAlquiler()}}>
                        <div className="form-floating mb-3">
                            <input type="text" className="form-control" value={sendalquilerdesc} onChange={e=>setsendalquilerdesc(e.target.value)} id="descripcion" placeholder="Local 1"/>
                            <label htmlFor="descripcion">Descripción</label>
                        </div>
                        <div className="form-floating">
                            <input type="text" className="form-control" value={sendalquilermonto} onChange={e=>setsendalquilermonto(e.target.value)} id="Monto" placeholder="Monto"/>
                            <label htmlFor="Monto">Monto / MES</label>
                        </div>

                        <select className="form-control form-control-lg" value={sendalquilersucursal} onChange={e=>setsendalquilersucursal(e.target.value)}>
                            <option value="">-SUCURSAL-</option>
                            {sucursales.map(e=>
                                <option key={e.id} value={e.id}>{e.codigo}</option>
                            )}
                        </select>

                        <div className="p-3 text-center">
                            {sendalquilerid?
                                <button className="btn btn-sinapsis">Editar</button>
                            :
                                <button className="btn btn-success">Guardar</button>
                            }
                        </div>
                    </form>
                </>
            :null}
        </div>
    )
}