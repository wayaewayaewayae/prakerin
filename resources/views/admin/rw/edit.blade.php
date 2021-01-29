@extends('layouts.admin')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            @if (session('message'))
                        <div class="alert alert-success" role="alert">
                            {{ session('message') }}
                        </div>
                @endif
                <div class="card">
                    <div class="card-header">
                        Edit Data Rw
                    </div>
                    <div class="card-body">
                        <form action="{{route('rw.update', $rw->id)}}" method="post">
                            @method('put')
                            @csrf
                            <div class="form-group">
                                <label for="">Pilih Desa</label>
                                <select name="id_desa" class="form-control">
                                    @foreach($desa as $data)
                                    <option value="{{$data->id}}" {{$data->id == $rw->id_desa ? 'selected' : ''}}>
                                            {{$data->nama_desa}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">No rw</label>
                                <input type="number" name="no_rw" value="{{$rw->no_rw}}" class="form-control" required>
                                @if($errors->has('no_rw'))
                                    <span class="text-danger">{{ $errors->first('no_rw') }}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn block">Simpan</button>
                                <a href=" {{ route('rw.index') }} " class="btn btn-danger">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection