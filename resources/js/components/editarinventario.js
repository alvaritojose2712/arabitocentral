export default function Editarinventario({
    productosInventario,
    changeInventarioModificarDiciModificarDici,
    guardarmodificarInventarioDici,

    inputBuscarInventario,
    setQBuscarInventario,
    Invnum,
    setInvnum,
    InvorderBy,
    setInvorderBy,
}){
    return 
        <div className="container-fluid">
            <form className="input-group" onSubmit={e=>{e.preventDefault();buscarInventario()}}>

                <div className="btn btn-success text-light" onClick={() => changeInventarioModificarDici(null, null, "add")}><i className="fa fa-plus"></i></div>
                <input type="text" ref={inputBuscarInventario} className="form-control" placeholder="Buscar...(esc)" onChange={e => setQBuscarInventario(e.target.value)} value={qBuscarInventario} />

                <select value={Invnum} onChange={e => setInvnum(e.target.value)}>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="500">500</option>
                    <option value="2000">2000</option>
                </select>
                <select value={InvorderBy} onChange={e => setInvorderBy(e.target.value)}>
                    <option value="asc">Asc</option>
                    <option value="desc">Desc</option>
                </select>
                
                <div className="btn btn-success text-light" onClick={guardarmodificarInventarioDici}><i className="fa fa-send"></i> GUARDAR</div>
            </form>
         
        </div>
    
}