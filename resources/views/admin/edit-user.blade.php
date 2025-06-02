@extends('layouts.admin')

@section('title', 'Editar Usuário')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Editar Usuário</h1>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Voltar
    </a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Editar Informações do Usuário</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome *</label>
                        <input type="text" 
                               class="form-control @error('name') is-invalid @enderror" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $user->name) }}" 
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $user->email) }}" 
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="password" class="form-label">Nova Senha</label>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Deixe em branco para manter a senha atual</div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar Nova Senha</label>
                        <input type="password" 
                               class="form-control" 
                               id="password_confirmation" 
                               name="password_confirmation">
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="card-title">Informações Adicionais</h6>
                        <p class="mb-1"><strong>ID:</strong> {{ $user->id }}</p>
                        <p class="mb-1"><strong>Criado em:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
                        <p class="mb-0"><strong>Última atualização:</strong> {{ $user->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header bg-danger text-white">
        <h5 class="card-title mb-0">Zona de Perigo</h5>
    </div>
    <div class="card-body">
        <p class="text-danger">
            <strong>Atenção:</strong> A exclusão do usuário é irreversível e também excluirá todos os dados financeiros associados.
        </p>
        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" 
              onsubmit="return confirm('Tem certeza que deseja excluir este usuário? Esta ação não pode ser desfeita!')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash"></i> Excluir Usuário
            </button>
        </form>
    </div>
</div>
@endsection
