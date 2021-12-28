
function Notificacion({msj,notificar}) {
	return (
		<div className="notificacion">
			<h5>Notificación: <i className="fa fa-times" onClick={()=>notificar("")}></i></h5>

			{msj}
		</div>
	)
}

export default Notificacion