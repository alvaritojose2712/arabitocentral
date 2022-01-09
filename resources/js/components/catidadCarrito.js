export default function CatidadCarrito({
	setshowCantidadCarrito,
	showCantidadCarrito,

	sucursales,
	ctSucursales,

  setctSucursales,
  setindexSelectCarrito,
  
  indexSelectCarrito,
  productosInventario,
  number,

  setCarrito,

  pedidoList,
	id_pedido,
	setid_pedido,

}){
	// const valctsucursales = e => {
	// 	let id = e.currentTarget.attributes["data-id"].value

	// 	if (ctSucursales.filter(e=>e.id==id).length) {
	// 		return ctSucursales.filter(e=>e.id==id)[0].val
	// 	}
	// 	return ""

	// }
	const onchangectsucursales = event => {
		let id = event.currentTarget.attributes["data-id"].value
		let val = number(event.currentTarget.value)

		if (ctSucursales.filter(e=>e.id==id).length) {
			setctSucursales(ctSucursales.map(e=>{
				if (e.id==id) {e.val = val} return e
			}))
		}else{
			setctSucursales(ctSucursales.concat({id,val,id_pedido:"nuevo"}))
		}

	}

	const onchangeid_pedido = event => {
		let id = event.currentTarget.attributes["data-id"].value
		let val = event.currentTarget.value

		if (ctSucursales.filter(e=>e.id==id).length) {
			setctSucursales(ctSucursales.map(e=>{
				if (e.id==id) {e.id_pedido = val} return e
			}))
		}else{
			setctSucursales(ctSucursales.concat({id,val:0,id_pedido:val}))
		}

	}

	

	const ctSucursalesCheck = () => {
		let res = ctSucursales.map(e=>e.val==""?0:parseFloat(e.val)).reduce((partial_sum, a) => partial_sum + a, 0)
		if (!res || res==0) {
			return 0
		}

		return res
	}

	let sumCt = ctSucursalesCheck()

	return(
		<div className="container-fluid">
			<div className="text-center"><i className="text-danger fa fa-times" onClick={()=>setshowCantidadCarrito("buscar")}></i></div>
			{indexSelectCarrito!==null&&productosInventario?
				productosInventario[indexSelectCarrito]?
					<div className="d-flex justify-content-between">
            <div className="w-50">
            	<span className="h6 text-muted font-italic">Bs. {productosInventario[indexSelectCarrito].bs}</span>
            	<br/>
            	<span className="h6 text-muted font-italic">COP. {productosInventario[indexSelectCarrito].cop}</span>
            	<br/>
            	<span className="h3 text-success">{productosInventario[indexSelectCarrito].precio}</span>
            	
            	<br/>
            	<span className="h6 text-muted">Ct. <b>{productosInventario[indexSelectCarrito].cantidad}</b> 
	            	{sumCt?
	            	<>
	            		<span className="text-danger">-{sumCt}</span> = {productosInventario[indexSelectCarrito].cantidad-sumCt} 
	            	</>
	            	:null}
            	</span>
            </div>
            <div className="w-50 text-right">
            	<small>{productosInventario[indexSelectCarrito].codigo_barras}</small><br/>
            	<small>{productosInventario[indexSelectCarrito].codigo_proveedor}</small><br/>
            	<span>{productosInventario[indexSelectCarrito].descripcion}</span>


            </div>
          </div>
				:null
			:null}
			<table className="table table-sm">
				<thead>
					<tr>
						<th>Ped. / Ct.</th>
						<th>Sucursal</th>
					</tr>
				</thead>
				<tbody>
					{sucursales.map(e=>
						<tr key={e.id}>
							<td className="align-middle">
								<div className="input-group w-75">
									<select className="form-control" 
										data-id={e.id}
										value={
											ctSucursales.filter(ee=>ee.id==e.id).length?ctSucursales.filter(ee=>ee.id==e.id)[0].id_pedido:"nuevo"
										} 
										onChange={onchangeid_pedido}>
										{pedidoList.filter(ee=>ee.id_sucursal==e.id).map(ee=>
											<option key={ee.id} value={ee.id}>{ee.id}</option>
										)}
											<option value="nuevo">Nuevo Pedido</option>
									</select>
									<input 
										type="text" 
										className="form-control w-50" 
										placeholder="Ct."
										value={
											ctSucursales.filter(ee=>ee.id==e.id).length?ctSucursales.filter(ee=>ee.id==e.id)[0].val:""
										}
										onChange={onchangectsucursales}
										data-id={e.id}
									/>
								</div>
							</td>
							<td> 
								{e.nombre} <br/>
								<span className="fst-italic text-muted">{e.codigo}</span></td>
						</tr>
					)}	
				</tbody>
			</table>
			{ctSucursalesCheck()?
				<div className="form-group d-flex justify-content-center">
					<button className="btn btn-circle btn-xl m-3 btn-outline-success" onClick={setCarrito} data-tipo="procesar"><i className="fa fa-cogs fa-3x"></i></button>
	    		<button className="btn btn-circle btn-xl m-3 btn-outline-arabito" onClick={setCarrito} data-tipo="buscar"><i className="fa fa-plus fa-3x"></i></button>
				</div>
			:null}
		</div>
	)
}