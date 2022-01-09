import BuscarProductosCarrito from '../components/buscarProductosCarrito';
import CantidadCarrito from '../components/catidadCarrito';

import PedidosList from '../components/pedidosList';
import PedidoSelect from '../components/pedidoSelect';

export default function Pedidos({
	inputBuscarInventario,
	qBuscarInventario,
	setQBuscarInventario,
	Invnum,
	setInvnum,
	InvorderColumn,
	setInvorderColumn,
	InvorderBy,
	setInvorderBy,
	productosInventario,

	indexSelectCarrito,
	setindexSelectCarrito,

	showCantidadCarritoFun,
	showCantidadCarrito,
	setshowCantidadCarrito,

	sucursales,

	ctSucursales,
  setctSucursales,
  number,

  setCarrito,

  pedidoList,
	id_pedido,
	setid_pedido,

	qpedido,
  setqpedido,
  qpedidoDateFrom,
  setqpedidoDateFrom,
  qpedidoDateTo,
  setqpedidoDateTo,
  qpedidoOrderBy,
  setqpedidoOrderBy,
  qpedidoOrderByDescAsc,
  setqpedidoOrderByDescAsc,
  pedidos,
  setpedidos,
  pedidoData,
  setpedidoData,

  qestadopedido,
	setqestadopedido,

	getPedidos,
	delPedido,
	selectPedido,

	moneda,

	setDelCarrito,
	setCtCarrito,
	setProdCarritoInterno,
	sendPedidoSucursal,
	showPedidoBarras,
}){
	return (
		<>
		{showCantidadCarrito=="buscar"?
			<BuscarProductosCarrito
				inputBuscarInventario={inputBuscarInventario}
				qBuscarInventario={qBuscarInventario}
				setQBuscarInventario={setQBuscarInventario}
				Invnum={Invnum}
				setInvnum={setInvnum}
				InvorderColumn={InvorderColumn}
				setInvorderColumn={setInvorderColumn}
				InvorderBy={InvorderBy}
				setInvorderBy={setInvorderBy}
				productosInventario={productosInventario}
				indexSelectCarrito={indexSelectCarrito}
				setindexSelectCarrito={setindexSelectCarrito}
				setshowCantidadCarrito={setshowCantidadCarrito}
				showCantidadCarrito={showCantidadCarrito}
				pedidoData={pedidoData}
				setProdCarritoInterno={setProdCarritoInterno}
			/>
		:null}

		{showCantidadCarrito=="carrito"?
			<CantidadCarrito
				setshowCantidadCarrito={setshowCantidadCarrito}
				showCantidadCarrito={showCantidadCarrito}

				sucursales={sucursales}
				ctSucursales={ctSucursales}
        setctSucursales={setctSucursales}
        setindexSelectCarrito={setindexSelectCarrito}
        indexSelectCarrito={indexSelectCarrito}
        productosInventario={productosInventario}
        number={number}
        setCarrito={setCarrito}

        pedidoList={pedidoList}
				id_pedido={id_pedido}
				setid_pedido={setid_pedido}

			/>
		:null}

		{showCantidadCarrito=="procesar"?
			<PedidosList
				qpedido={qpedido}
        setqpedido={setqpedido}
        qpedidoDateFrom={qpedidoDateFrom}
        setqpedidoDateFrom={setqpedidoDateFrom}
        qpedidoDateTo={qpedidoDateTo}
        setqpedidoDateTo={setqpedidoDateTo}
        qpedidoOrderBy={qpedidoOrderBy}
        setqpedidoOrderBy={setqpedidoOrderBy}
        qpedidoOrderByDescAsc={qpedidoOrderByDescAsc}
        setqpedidoOrderByDescAsc={setqpedidoOrderByDescAsc}
        pedidos={pedidos}
        setpedidos={setpedidos}

        qestadopedido={qestadopedido}
				setqestadopedido={setqestadopedido}

				getPedidos={getPedidos}
				delPedido={delPedido}
				selectPedido={selectPedido}
				moneda={moneda}

				setshowCantidadCarrito={setshowCantidadCarrito}
			/>
		:null}

		{showCantidadCarrito=="pedidoSelect"?
			<PedidoSelect
				setshowCantidadCarrito={setshowCantidadCarrito}
				pedidoData={pedidoData}
				setDelCarrito={setDelCarrito}
				setCtCarrito={setCtCarrito}
				delPedido={delPedido}
				moneda={moneda}
				sendPedidoSucursal={sendPedidoSucursal}
				showPedidoBarras={showPedidoBarras}


			/>
		:null}
			

		</>
	)
}