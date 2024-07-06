export default function GestionarnombresInventario({
    buscarNombres,
    qnombres,
    setqnombres,
    qtiponombres,
    setqtiponombres,
    datanombres,
    modNombres,
    newNombres,
}){
    return (
        <div className="container">
            <div className="form-group">
                <form onSubmit={(e)=>{buscarNombres();e.preventDefault()}} className="input-group">
                    <input type="text" className="form-control" placeholder={"Buscar en "+qtiponombres} value={qnombres} onChange={e=>setqnombres(e.target.value)} />
                    <select className="form-control" value={qtiponombres} onChange={e=>setqtiponombres(e.target.value)}>
                        <option value="n1">n1</option>
                        <option value="n2">n2</option>
                        <option value="n3">n3</option>
                        <option value="n4">n4</option>
                        <option value="n5">n5</option>
                        <option value="id_marca">id_marca</option>
                        <option value="id_categoria">id_categoria</option>
                        <option value="id_catgeneral">id_catgeneral</option>
                    </select>
                    <button className="btn btn-success"><i className="fa fa-search"></i></button>
                </form>

                <table className="table">
                    <tbody>
                        {datanombres?datanombres.map(e=>
                            <tr key={e.id}>
                                <td>{e.tipo}</td>
                                <td>{e.id}</td>
                                <td>{e.nombre?e.nombre:e.descripcion}</td>
                                <td>
                                    <div className="btn btn-group">
                                        <button className="btn btn-danger" onClick={()=>modNombres(e.id,e.tipo,"eliminar")}><i className="fa fa-times"></i></button>
                                        <button className="btn btn-warning" onClick={()=>modNombres(e.id,e.tipo,"editar")}><i className="fa fa-pencil"></i></button>
                                        <button className="btn btn-success" onClick={()=>newNombres(e.id,e.tipo,"nuevo")}>NEW</button>
                                    </div>
                                </td>
                            </tr>
                        ):null}
                    </tbody>
                </table>
            </div>
        </div>
    )
}