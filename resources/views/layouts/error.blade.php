
  @if ($errors->any())
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>¡Ups!</strong> Tenemos problemas. <br>
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>

        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
  @endif


  @if(isset($msj))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong><i class="fa fa-check"></i> ¡Éxito! </strong> {{ $msj }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
  @endif
  