<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Exchange rate list</title>
</head>
<body>
<div class="container">
    <h1>Exchange rate</h1>
    <form action="{{ route('exchange-rate.list') }}" method="GET">
        <div class="row">
            <div class="col-3">
                <label for="date" class="col-1 col-form-label">Date</label>
                <input type="date" class="form-control" id="date" name="date"/>
            </div>
            <div class="col-3">
                <label for="currency" class="form-label">Currencies</label>
                <select id="currency" class="form-select" name="currency">
                    @if(!empty($currencies))
                        @foreach($currencies as $currency)
                            <option
                                value="{{$currency->id}}" {{ !empty($filters['currency']) && $currency->id == $filters['currency'] ? 'selected' : null }}>{{ $currency->name }}</option>
                        @endforeach
                    @else
                        <option disabled>No options</option>
                    @endif
                </select>
            </div>
            <div class="col-3">
                <label for="bank" class="form-label">Currencies</label>
                <select id="bank" class="form-select" name="bank">
                    @if(!empty($banks))
                        @foreach($banks as $bank)
                            <option
                                value="{{$bank->id}}" {{ !empty($filters['bank']) && $bank->id == $filters['bank'] ? 'selected' : null }}>{{ $bank->name }}</option>
                        @endforeach
                    @else
                        <option disabled>No options</option>
                    @endif
                </select>
            </div>
            <div class="col-3">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>

    </form>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Code</th>
            <th scope="col">Value</th>
        </tr>
        </thead>
        <tbody>
        @if($exchange_rates->isNotEmpty())
            @foreach($exchange_rates as $exchange_rate)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $exchange_rate->currency->name }}</td>
                    <td>{{ $exchange_rate->currency->code }}</td>
                    <td>{{ $exchange_rate->value }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="4" style="text-align: center">No data.</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
</body>
</html>
