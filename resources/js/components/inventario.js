import Proveedores from '../components/proveedores';
import CargarProducto from '../components/cargarproducto';
import Facturas from '../components/facturas';

export default function Inventario({
	productosInventario,
  qBuscarInventario,
  setQBuscarInventario,

  setIndexSelectInventario,
  indexSelectInventario,

  inputBuscarInventario,

  inpInvbarras,
  setinpInvbarras,
  inpInvcantidad,
  setinpInvcantidad,
  inpInvalterno,
  setinpInvalterno,
  inpInvunidad,
  setinpInvunidad,
  inpInvcategoria,
  setinpInvcategoria,
  inpInvdescripcion,
  setinpInvdescripcion,
  inpInvbase,
  setinpInvbase,
  inpInvventa,
  setinpInvventa,
  inpInviva,
  setinpInviva,

  number,
  guardarNuevoProducto,

  setProveedor,
  proveedordescripcion,
  setproveedordescripcion,
  proveedorrif,
  setproveedorrif,
  proveedordireccion,
  setproveedordireccion,
  proveedortelefono,
  setproveedortelefono,

  subViewInventario,
  setsubViewInventario,

  setIndexSelectProveedores,
  indexSelectProveedores,
  qBuscarProveedor,
  setQBuscarProveedor,
  proveedoresList,

  delProveedor,
  delProducto,

  inpInvid_proveedor,
  setinpInvid_proveedor,
  inpInvid_marca,
  setinpInvid_marca,
  inpInvid_deposito,
  setinpInvid_deposito,

  depositosList,

  setshowModalFacturas,
  showModalFacturas,
  facturas,

  factqBuscar,
  setfactqBuscar,
  factqBuscarDate,
  setfactqBuscarDate,
  factsubView,
  setfactsubView,
  factSelectIndex,
  setfactSelectIndex,
  factOrderBy,
  setfactOrderBy,
  factOrderDescAsc,
  setfactOrderDescAsc,
  factInpid_proveedor,
  setfactInpid_proveedor,
  factInpnumfact,
  setfactInpnumfact,
  factInpdescripcion,
  setfactInpdescripcion,
  factInpmonto,
  setfactInpmonto,
  factInpfechavencimiento,
  setfactInpfechavencimiento,

  setFactura,

  factInpestatus,
  setfactInpestatus,

  delFactura,

  Invnum,
  setInvnum,
  InvorderColumn,
  setInvorderColumn,
  InvorderBy,
  setInvorderBy,

  delItemFact,

  subviewProveedores,
	setsubviewProveedores,

	subviewCargarProductos,
	setsubviewCargarProductos,

	moneda,
}) {
	return (
		 <>
      <div className="container">
        <div className="row">
	        <div className="col">

	          <div className="btn-group mb-2">              
	              <button className={("btn btn-sm ")+(subViewInventario=="facturas"?"btn-dark":"btn-outline-arabito")} onClick={()=>setsubViewInventario("facturas")}>Facturas</button>
	              <button className={("btn btn-sm ")+(subViewInventario=="proveedores"?"btn-dark":"btn-outline-arabito")} onClick={()=>setsubViewInventario("proveedores")}>Proveedores</button>
	              {factSelectIndex!==null?<button className={("btn btn-sm ")+(subViewInventario=="inventario"?"btn-dark":"btn-outline-arabito")} onClick={()=>setsubViewInventario("inventario")}>Inventario</button>
	              :null}
	          </div>
	        </div>
          {factSelectIndex==null?null
          : 
	          <div className="col shadow ">
	          		
	          	<div className="card-pedido mt-3">
	              <h4 className="text-right d-flex align-items-center">
	                <span className="badge bg-secondary pointer"  onClick={()=>setfactSelectIndex(null)}><i className="fa fa-arrow-left"></i> {facturas[factSelectIndex]?facturas[factSelectIndex].numfact:null}</span> 
	                {facturas[factSelectIndex]?facturas[factSelectIndex].proveedor.descripcion:null}  
	              </h4>
	          	</div>
	          </div>
          }
          
        </div>
      </div>
      <hr/>
      {subViewInventario=="facturas"?<Facturas
        facturas={facturas}

        factqBuscar={factqBuscar}
        setfactqBuscar={setfactqBuscar}
        factqBuscarDate={factqBuscarDate}
        setfactqBuscarDate={setfactqBuscarDate}
        factsubView={factsubView}
        setfactsubView={setfactsubView}
        factSelectIndex={factSelectIndex}
        setfactSelectIndex={setfactSelectIndex}
        factOrderBy={factOrderBy}
        setfactOrderBy={setfactOrderBy}
        factOrderDescAsc={factOrderDescAsc}
        setfactOrderDescAsc={setfactOrderDescAsc}
        factInpid_proveedor={factInpid_proveedor}
        setfactInpid_proveedor={setfactInpid_proveedor}
        factInpnumfact={factInpnumfact}
        setfactInpnumfact={setfactInpnumfact}
        factInpdescripcion={factInpdescripcion}
        setfactInpdescripcion={setfactInpdescripcion}
        factInpmonto={factInpmonto}
        setfactInpmonto={setfactInpmonto}
        factInpfechavencimiento={factInpfechavencimiento}
        setfactInpfechavencimiento={setfactInpfechavencimiento}
        setFactura={setFactura}
        proveedoresList={proveedoresList}

        number={number}
        
        factInpestatus={factInpestatus}
        setfactInpestatus={setfactInpestatus}
        delFactura={delFactura}
        delItemFact={delItemFact}

        moneda={moneda}
      />
      :null}
      {factSelectIndex!==null?
        subViewInventario=="inventario"?
          <CargarProducto 
            productosInventario={productosInventario}
            qBuscarInventario={qBuscarInventario}
            setQBuscarInventario={setQBuscarInventario}

            setIndexSelectInventario={setIndexSelectInventario}
            indexSelectInventario={indexSelectInventario}
            inputBuscarInventario={inputBuscarInventario}

            inpInvbarras={inpInvbarras}
            setinpInvbarras={setinpInvbarras}
            inpInvcantidad={inpInvcantidad}
            setinpInvcantidad={setinpInvcantidad}
            inpInvalterno={inpInvalterno}
            setinpInvalterno={setinpInvalterno}
            inpInvunidad={inpInvunidad}
            setinpInvunidad={setinpInvunidad}
            inpInvcategoria={inpInvcategoria}
            setinpInvcategoria={setinpInvcategoria}
            inpInvdescripcion={inpInvdescripcion}
            setinpInvdescripcion={setinpInvdescripcion}
            inpInvbase={inpInvbase}
            setinpInvbase={setinpInvbase}
            inpInvventa={inpInvventa}
            setinpInvventa={setinpInvventa}
            inpInviva={inpInviva}
            setinpInviva={setinpInviva}

            number={number}

            guardarNuevoProducto={guardarNuevoProducto}

            setProveedor={setProveedor}
            proveedordescripcion={proveedordescripcion}
            setproveedordescripcion={setproveedordescripcion}
            proveedorrif={proveedorrif}
            setproveedorrif={setproveedorrif}
            proveedordireccion={proveedordireccion}
            setproveedordireccion={setproveedordireccion}
            proveedortelefono={proveedortelefono}
            setproveedortelefono={setproveedortelefono}

            subViewInventario={subViewInventario}
            setsubViewInventario={setsubViewInventario}

            setIndexSelectProveedores={setIndexSelectProveedores}
            indexSelectProveedores={indexSelectProveedores}
            qBuscarProveedor={qBuscarProveedor}
            setQBuscarProveedor={setQBuscarProveedor}
            proveedoresList={proveedoresList}

            delProveedor={delProveedor}
            delProducto={delProducto}

            inpInvid_proveedor={inpInvid_proveedor}
            setinpInvid_proveedor={setinpInvid_proveedor}
            inpInvid_marca={inpInvid_marca}
            setinpInvid_marca={setinpInvid_marca}
            inpInvid_deposito={inpInvid_deposito}
            setinpInvid_deposito={setinpInvid_deposito}
            
            depositosList={depositosList}
     

            Invnum={Invnum}
            setInvnum={setInvnum}
            InvorderColumn={InvorderColumn}
            setInvorderColumn={setInvorderColumn}
            InvorderBy={InvorderBy}
            setInvorderBy={setInvorderBy}

            subviewCargarProductos={subviewCargarProductos}
						setsubviewCargarProductos={setsubviewCargarProductos}
          />
        :null

      :null}
      {subViewInventario=="proveedores"?<Proveedores 

        number={number}
        setProveedor={setProveedor}
        proveedordescripcion={proveedordescripcion}
        setproveedordescripcion={setproveedordescripcion}
        proveedorrif={proveedorrif}
        setproveedorrif={setproveedorrif}
        proveedordireccion={proveedordireccion}
        setproveedordireccion={setproveedordireccion}
        proveedortelefono={proveedortelefono}
        setproveedortelefono={setproveedortelefono}
        subViewInventario={subViewInventario}
        setsubViewInventario={setsubViewInventario}
        setIndexSelectProveedores={setIndexSelectProveedores}
        indexSelectProveedores={indexSelectProveedores}
        qBuscarProveedor={qBuscarProveedor}
        setQBuscarProveedor={setQBuscarProveedor}
        proveedoresList={proveedoresList}
        delProveedor={delProveedor}
        delProducto={delProducto}
        inpInvid_proveedor={inpInvid_proveedor}
        setinpInvid_proveedor={setinpInvid_proveedor}
        inpInvid_marca={inpInvid_marca}
        setinpInvid_marca={setinpInvid_marca}
        inpInvid_deposito={inpInvid_deposito}
        setinpInvid_deposito={setinpInvid_deposito}
        depositosList={depositosList}
        

        subviewProveedores={subviewProveedores}
				setsubviewProveedores={setsubviewProveedores}


      />:null}

    </>
	)
}