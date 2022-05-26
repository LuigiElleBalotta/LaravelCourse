<div class="card" style="width: 18rem;">
    <img class="card-img-top" src="{{$img_url}}" alt="{{$img_title}}">
    <div class="card-body">
        <h5 class="card-title">{{$img_title}}</h5>
        <!-- La variabile slot contiene il contenuto html tra component e endcomponent -->
        {{$slot}}
    </div>
</div>
