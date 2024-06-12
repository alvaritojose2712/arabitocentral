import { useEffect, useState } from "react";
import Chart from "react-apexcharts";

export default function Gastos({
	categoriasCajas,
	formatAmount,
	nominaData,
	gastosData,
	gastosQ,
	setgastosQ,
	gastosQCategoria,
	setgastosQCategoria,
	gastosQFecha,
	setgastosQFecha,
	gastosQFechaHasta,
	setgastosQFechaHasta,

	gastosQsucursal,
	setgastosQsucursal,

	gastoscatgeneral,
	setgastoscatgeneral,
	gastosingreso_egreso,
	setgastosingreso_egreso,
	gastostypecaja,
	setgastostypecaja,
	gastosorder,
	setgastosorder,
	gastosfieldorder,
	setgastosfieldorder,
	

	gastosDescripcion,
	setgastosDescripcion,
	gastosMonto,
	setgastosMonto,
	gastosCategoria,
	setgastosCategoria,
	gastosBeneficiario,
	setgastosBeneficiario,
	gastosFecha,
	setgastosFecha,
	setgastosMonto_dolar,              
	gastosMonto_dolar,
	setgastosTasa,              
	gastosTasa,

	subviewGastos,
	setsubviewGastos,

	selectIdGastos,
	setselectIdGastos,
	delGasto,
	saveNewGasto,
	getGastos,
	setNewGastosInput,
	setEditGastosInput,

	qBeneficiario,
	setqBeneficiario,
	qSucursal,
	setqSucursal,

	qCatGastos,
	setqCatGastos,

	getSucursales,
	sucursales,
	getPersonal,
	qNomina,
	setqNomina,
	modeMoneda,
	setmodeMoneda,
	modeEjecutor,
	setmodeEjecutor,
	addBeneficiarioList,
	listBeneficiario,
	setlistBeneficiario,
	gastosBanco,
	setgastosBanco,
	opcionesMetodosPago,
	moneda,
	colorSucursal,
	colorsGastosCat,

	distribucionGastosCat,
	getGastosDistribucion,

}) {

	/* useEffect(()=>{
		getPersonal(data=>{
			if (data.personal.length) {
				let id = data.personal[0].id
				if (qNomina) {
					setgastosBeneficiario(id)
				}
			}
		})
		if (qNomina=="") {
			setgastosBeneficiario("")
		}
	},[qNomina]) */

	/* useEffect(()=>{
		getSucursales(qSucursal,data=>{
			if (data.length) {
				let id = data[0].id
				if (qSucursal) {
					setgastosBeneficiario(id)
				}
			}
		})
		if (qSucursal=="") {
			setgastosBeneficiario("")
		}
	},[qSucursal]) */

	useEffect(()=>{
		getGastos()
	},[
		gastosQCategoria,
		gastoscatgeneral,
		gastosingreso_egreso,
		gastosorder,
		gastosfieldorder,
		gastosQsucursal,
	])

	useEffect(()=>{
		setlistBeneficiario([])
	},[modeEjecutor])

	const [qbuscarcat,setqbuscarcat] = useState("")

	
	return(
		<div className="container-fluid">
			<div className="d-flex justify-content-center">
                <div className="btn-group m-1">
                    <button className={("btn btn-sm ")+(subviewGastos=="cargar"?"btn-sinapsis":"")} onClick={()=>setsubviewGastos("cargar")}>Cargar</button>
                    <button className={("btn btn-sm ")+(subviewGastos=="resumen"?"btn-sinapsis":"")} onClick={()=>setsubviewGastos("resumen")}>Destalles</button>
                    <button className={("btn btn-sm ")+(subviewGastos=="distribucion"?"btn-sinapsis":"")} onClick={()=>setsubviewGastos("distribucion")}>Resumen</button>
                </div>
            </div>

			{subviewGastos=="cargar"?
				<div className="was-validated">
					<div className="form-group mb-2">
						<span className="form-label">Descripción</span>
						<input type="text" className="form-control form-control-lg" value={gastosDescripcion} onChange={e=>setgastosDescripcion(e.target.value)} placeholder="Descripción" required={true}/>
					</div>

					{modeMoneda=="dolar"?
						<div className="form-group mb-2">
							<span className="form-label text-success">Monto $</span>
							<div className="input-group">
								<input type="text" className="form-control text-success fs-2" value={gastosMonto_dolar} onChange={e=>setgastosMonto_dolar(formatAmount(e.target.value,"$ "))} placeholder="Monto $" required={true}/>
								<button className="btn btn-sinapsis" type="button" onClick={()=>setmodeMoneda("bs")}><i className="fa fa-refresh"></i> Bs</button>
							</div>
						</div>
					:null}

					{modeMoneda=="bs"?
						<div className="form-group mb-2">
							<span className="form-label text-sinapsis">Monto Bs </span>
							<div className="row">
								<div className="col">
									<input type="text" className="form-control text-sinapsis fs-2" value={gastosMonto} onChange={e=>setgastosMonto(formatAmount(e.target.value,"Bs. "))} placeholder="Monto Bs" required={true} />
								</div>
								<div className="col-3">
									<div className="input-group">
										<input type="text" className="form-control text-sinapsis fs-2" value={gastosTasa} onChange={e=>setgastosTasa(e.target.value)} placeholder="Tasa" required={true} />
										<button className="btn btn-success" type="button" onClick={()=>setmodeMoneda("dolar")}><i className="fa fa-refresh"></i> $</button>

									</div>
								</div>
							</div>
						</div>
					:null}
					<div className="form-group mb-2">
						<select className="form-control" 
						value={gastosBanco} 
						onChange={e=>setgastosBanco(e.target.value)} required={true}>
							<option value="">-Método-</option>
							{opcionesMetodosPago.map(e=>
								<option value={e.codigo} key={e.id}>{e.descripcion}</option>
							)}
						</select>
					</div>
					<div className="form-group mb-2">
						<span className="form-label">Fecha</span>
						<input type="date" className="form-control form-control-lg" value={gastosFecha} onChange={e=>setgastosFecha(e.target.value)} required={true} />
					</div>

					<div className="form-group mb-2">
						<span className="form-label">Categoría</span>
						
						<input type="text" className="form-control" placeholder="Buscar CATEGORÍA..." value={qbuscarcat} onChange={event=>setqbuscarcat(event.target.value)} />

						<div className="card card-personal">
							<ul className="list-group">
								{categoriasCajas.length?categoriasCajas.filter(e=>{
									if(qbuscarcat==""){return true}
									else{
										if ((e.nombre.toLowerCase()).indexOf(qbuscarcat.toLowerCase())!==-1) {return true}else{return false}
									}
								}).map(e=>
									<li key={e.id} className={"list-group-item "+(gastosCategoria==e.id?" active pointer ":"")} onClick={()=>{setgastosCategoria(e.id)}}>{e.nombre}</li>
								):null}
							</ul>
						</div>
					</div>

					<div className="form-group mb-2">
						<div className="mb-1">
							<span className="form-label">Asignar a </span>
							
							{modeEjecutor=="personal"?<button className="btn btn-sinapsis" type="button" onClick={()=>setmodeEjecutor("sucursal")}><i className="fa fa-user"></i> </button>:null}
							{modeEjecutor=="sucursal"?<button className="btn btn-success" type="button" onClick={()=>setmodeEjecutor("personal")}><i className="fa fa-home"></i></button>:null}
						</div>


							{modeEjecutor=="personal"?
								<div className="row">
									<div className="col-md-2">
										<div className="input-group">
											
											<form onSubmit={event=>{event.preventDefault();getPersonal()}} className="input-group">
												<input type="text" className="form-control is-invalid" value={qNomina} onChange={e=>setqNomina(e.target.value)} placeholder="Buscar PERSONAL..." />
												<button className="btn btn-success" type="submit"> <i className="fa fa-search"></i> </button>
											</form>
										</div>
									</div>
									<div className="col">
										<div className="input-group">
											{/* <button className="btn btn-success" type="button" onClick={()=>addBeneficiarioList("add")}><i className="fa fa-arrow-right"></i></button>*/}
											{/* <select className={("form-select ")} 
											value={gastosBeneficiario} 
											onChange={e=>setgastosBeneficiario(e.target.value)} required={true}>
												<option value="">-Personal-</option>
												{nominaData.personal?nominaData.personal.length?nominaData.personal.map(e=>
													<option value={e.id} key={e.id}>{e.nominanombre} {e.nominacedula}</option>
												):null:null}
											</select> */}

											<div className="card card-personal">
                                                <ul className="list-group">
													{nominaData.personal?nominaData.personal.length?nominaData.personal.map(e=>
														<li key={e.id} className={"list-group-item "+(listBeneficiario.filter(ee=>ee.id==e.id).length?" active pointer ":"")} onClick={()=>{setgastosBeneficiario(e.id);addBeneficiarioList("add",e.id)}}>{e.nominanombre} {e.nominacedula}</li>
													):null:null}
												</ul>
                                            </div>
										</div>
									</div>
								</div>
							:null}

							{modeEjecutor=="sucursal"?
								<div className="row">
									<div className="col-md-2">
										<div className="input-group">
											
											<form onSubmit={event=>{event.preventDefault();getSucursales(qSucursal)}} className="input-group">
												<input type="text" className="form-control is-invalid" value={qSucursal} onChange={e=>{setqSucursal(e.target.value)}} placeholder="Buscar SUCURSALES..." />
											</form>
										</div>
									</div>
									<div className="col">
										<div className="card card-personal">
											<ul className="list-group">
												{sucursales.length?sucursales.map(e=>
													<li key={e.id} className={"list-group-item "+(listBeneficiario.filter(ee=>ee.id==e.id).length?" active pointer ":"")} onClick={()=>{setgastosBeneficiario(e.id);addBeneficiarioList("add",e.id)}}>{e.codigo}</li>
												):null}
											</ul>
										</div>
									</div>
								</div>
							:null}
							
						

						{listBeneficiario.length?
							<div className="border bg-light p-3 m-2">
								{listBeneficiario.map(e=>
									<button key={e.id} className="btn mb-1 me-1" onClick={()=>addBeneficiarioList("del",e.id)} style={{backgroundColor:e.color?e.color:"coral"}} onDoubleClick={()=>addBeneficiarioList("del",e.id)}>
										{e.codigo?e.codigo:e.nominanombre}
									</button>	
								)}
							</div>
						:null}
					</div>

					<div className="text-center">
						<button className="btn btn-success btn-lg" onClick={()=>saveNewGasto()}><i className="fa fa-save"></i> Guardar</button>
					</div>
				</div>				
			:null}

			{subviewGastos=="resumen"?
				<>
					<form onSubmit={event=>{
						event.preventDefault()
						getGastos()
					}}>
						<div className="input-group">
							<input type="text" className="form-control fs-6" value={gastosQ} onChange={e=>setgastosQ(e.target.value)} placeholder="Buscar..."/>
							<select className="form-control" 
							value={gastosQCategoria} 
							onChange={e=>setgastosQCategoria(e.target.value)}>
								<option value="">-Buscar por Categoría-</option>
								{categoriasCajas.map(e=>
									<option value={e.id} key={e.id}>{e.nombre}</option>
								)}
							</select>
							
							<select className="form-control" 
							value={gastosQsucursal} 
							onChange={e=>setgastosQsucursal(e.target.value)}>
								<option value="">-Buscar por Sucursal-</option>
								{sucursales.map(e=>
									<option value={e.id} key={e.id}>{e.nombre}</option>
								)}
							</select>
							
							<input type="date" className="form-control fs-6" value={gastosQFecha} onChange={e=>setgastosQFecha(e.target.value)} />
							<input type="date" className="form-control fs-6" value={gastosQFechaHasta} onChange={e=>setgastosQFechaHasta(e.target.value)} />

							<button className="btn btn-success"><i className="fa fa-search"></i></button>
						</div>
					</form>
					<table className="table">
						<thead>
							<tr>
								<th className="pointer" onClick={()=>{setgastosfieldorder("created_at");setgastosorder(gastosorder=="desc"?"asc":"desc")}}>FECHA</th>
								<th className="pointer" onClick={()=>{setgastosfieldorder("id_sucursal");setgastosorder(gastosorder=="desc"?"asc":"desc")}}>SUCURSAL / PERSONA</th>
								<th className="pointer">DESCRIPCIÓN</th>
								{/* <th className="bg-warning text-danger fs-6 text-right">
									{gastosData?gastosData.sum?(
										<span>
											{moneda(gastosData.sum)}
										</span>
									):null:null}
								</th> */}
								<th className="pointer text-center" onClick={()=>{setgastosfieldorder("categoria");setgastosorder(gastosorder=="desc"?"asc":"desc")}}>CATEGORÍA</th>
								<th className="pointer text-center" onClick={()=>{setgastosfieldorder("catgeneral");setgastosorder(gastosorder=="desc"?"asc":"desc")}}>CATGENERAL</th>
								<th className="pointer text-center" onClick={()=>{setgastosfieldorder("ingreso_egreso");setgastosorder(gastosorder=="desc"?"asc":"desc")}}>TIPO</th>
								<th className="pointer text-right" onClick={()=>{setgastosfieldorder("montodolar");setgastosorder(gastosorder=="desc"?"asc":"desc")}}>MONTO</th>
							</tr>
						</thead>
						<tbody>
							{gastosData?gastosData.data?gastosData.data.map(e=>
								<tr key={e.id}>
									<td>{e.created_at}</td>
									<td className="text-center">
										{e.sucursal?
											<>
												<button className={"btn w-100 fw-bolder fs-6"} style={{backgroundColor:colorSucursal(e.sucursal.codigo)}}>
													{e.sucursal.codigo}
												</button>
												{e.origen? <b>CARGA ADMINISTRACIÓN</b> :null}
											</>
										:null}
										{e.beneficiario?" / "+e.beneficiario.nominanombre:null}
									</td>
									
									<td>{e.concepto?e.concepto:(e.loteserial?e.loteserial:null)}</td>
									<td> 
										{e.cat?
											<button className={"btn w-100 fw-bolder fs-6"} style={{backgroundColor:colorsGastosCat(e.cat.id,"cat","color")}}>
												{colorsGastosCat(e.cat.id,"cat","desc")}
											</button>
										:null}
									</td>
									<td> 
										{e.cat?
											<button className={"btn w-100 fw-bolder fs-6"} style={{backgroundColor:colorsGastosCat(e.cat.catgeneral,"catgeneral","color")}}>
												{colorsGastosCat(e.cat.catgeneral,"catgeneral","desc")}
											</button>
										:null}
									</td>
									<td> 
										{e.cat?
											<button className={"btn w-100 fw-bolder fs-6"} style={{backgroundColor:colorsGastosCat(e.cat.ingreso_egreso,"ingreso_egreso","color")}}>
												{colorsGastosCat(e.cat.ingreso_egreso,"ingreso_egreso","desc")}
											</button>
										:null}
									</td>
									
									<td className={("fs-6 text-right ")+(e.montodolar<0?"text-danger":"text-success")}>{moneda(e.montodolar)}</td>
								</tr>
							):null:null}
						</tbody>
					</table>
				</>
			:null}

			{subviewGastos=="distribucion"?
				<>
					<form onSubmit={event=>{
						event.preventDefault()
						getGastosDistribucion()
					}}>
						<div className="input-group">
							<input type="date" className="form-control fs-6" value={gastosQFecha} onChange={e=>setgastosQFecha(e.target.value)} />
							<input type="date" className="form-control fs-6" value={gastosQFechaHasta} onChange={e=>setgastosQFechaHasta(e.target.value)} />

							<button className="btn btn-success"><i className="fa fa-search"></i></button>
						</div>
					</form>

					<div className="row">
						<div className="col text-dark">
							{distribucionGastosCat.distribucionGastosCat?
								Object.entries(distribucionGastosCat.distribucionGastosCat).map((ingregre,i)=>
									ingregre[0]==2||ingregre[0]==3?<>
										{ingregre[0]==2||ingregre[0]==3?<Chart
											options={{chart: {width: 1200,type: 'pie',}
											,dataLabels: {
												style: {
												  colors: ['#000']
												}
											}
											,colors: ingregre[1]["data"].map(e=>colorsGastosCat(e.id,"cat","color")) 
											,labels: ingregre[1]["data"]? ingregre[1]["data"].map(e=>e.nombre) : []
											,responsive: [{breakpoint: 480,options: {chart: {width: 200},legend: {position: 'bottom'}}}]}}
											series={
												ingregre[1]["data"]? ingregre[1]["data"].map(e=>Math.abs(e.sum)) : []
											} 
											type="pie" width="1200"
										/>:null}
										<table className="table mb-2">
											<tbody>
												{ingregre[1]["data"].map(e=>
													<tr key={e.id}>
														<td className="cell3">
															<button className={"btn w-100 fw-bolder fs-6"} style={{backgroundColor:colorsGastosCat(e.catgeneral,"catgeneral","color")}}>
																{colorsGastosCat(e.catgeneral,"catgeneral","desc")}
															</button>
														</td>
														<td className="cell3">
															<button className={"btn w-100 fw-bolder fs-6"} style={{backgroundColor:colorsGastosCat(ingregre[0],"ingreso_egreso","color")}}>
																{colorsGastosCat(e.ingreso_egreso,"ingreso_egreso","desc")}
															</button>
														</td>
														<td className="cell5">
															<button className={"btn w-100 fw-bolder fs-6"} style={{backgroundColor:colorsGastosCat(e.id,"cat","color")}}>
																{e.nombre}
															</button>
														</td>
														<td className="fs-6 text-right text-danger cell1">{moneda(e.sum)}</td>
														<td className="text-muted fst-italic text-right cell1">{(e.por)}%</td>
													</tr>
												)}
												<tr>
													<td></td>
													<td></td>
													<td colSpan={3} className="bg-warning fs-6 text-danger text-right">{ingregre[1]["sum"]?moneda(ingregre[1]["sum"]):0}</td>
												</tr>
											</tbody>
										</table>
									</>:null
								)
							:null}
						</div>
						<div className="col">
							{distribucionGastosCat.distribucionGastosSucursal?
								Object.entries(distribucionGastosCat.distribucionGastosSucursal).map((ingregre,i)=>
									<table className="table mb-3">
										<tbody>
											{ingregre[1]["data"].map(e=>
												<tr key={e.id}>
													<td className=" cell3">
														<button className={"btn w-100 fw-bolder fs-6"} style={{backgroundColor:colorsGastosCat(ingregre[0],"ingreso_egreso","color")}}>
															{colorsGastosCat(ingregre[0],"ingreso_egreso","desc")}
														</button>
													</td>
													<td className=" cell5">
														<button className={"btn w-100 fw-bolder fs-6"} style={{backgroundColor:colorsGastosCat(e.id,"cat","color")}}>
															{e.nombre}
														</button>
													</td>
													<td className="fs-6 text-right text-danger cell1">{moneda(e.sum)}</td>
													<td className="text-muted fst-italic text-right cell1">{(e.por)}%</td>
												</tr>
											)}
											<tr>
												<td></td>
												<td></td>
												<td colSpan={2} className="bg-warning fs-6 text-danger text-right">{ingregre[1]["sum"]?moneda(ingregre[1]["sum"]):0}</td>
											</tr>
										</tbody>
									</table>
								)
							:null}
						</div>
					</div>
				</>
			:null}
			
		</div>
	)
}