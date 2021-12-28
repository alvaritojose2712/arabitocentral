import React, {Component} from 'react';

function Cargando(props) {
	return(
		<div className="loaders text-center cargando btn-arabito p-2 rounded" >
			<div className="loader" style={{display:props.active?"":"none"}}>
						Trabajando...
		        <div className="loader-inner ball-pulse">
		          <div className="bg-arabito"></div>
		          <div className="bg-arabito"></div>
		          <div className="bg-arabito"></div>
		        </div>
		     </div>
		</div>
	);
}
export default Cargando;