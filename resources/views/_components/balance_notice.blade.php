<div class="card card-info">
    <div class="card-body">
        <p class="text-center">
            <strong>Total Account Balance @ {{$NOW}}</strong><br/>
            <strong class="h1 money no-wrap" style="font-size: 3em;">
                {{$user->getTotalBalanceStr()}}
            </strong>
        </p>
    </div>
</div>
