@extends('layouts.admin')

@section('title', 'Dashboard Administrativo')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-speedometer2"></i> Dashboard Administrativo</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus"></i> Novo Usuário
        </a>
        <button type="button" class="btn btn-outline-secondary ms-2" onclick="location.reload()">
            <i class="bi bi-arrow-clockwise"></i> Atualizar
        </button>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card card-stats">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Total de Usuários</h5>
                        <h2 class="mb-0">{{ $users->count() }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-people-fill" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Usuários com Dados</h5>
                        <h2 class="mb-0">{{ $users->where('financas_count', '>', 0)->count() }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-database-fill" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">API Status</h5>
                        <h2 class="mb-0">
                            <i class="bi bi-check-circle"></i> Online
                        </h2>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-cloud-check-fill" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="bi bi-people"></i> Gerenciar Usuários
        </h5>
    </div>
    <div class="card-body">
        @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Criado em</th>
                            <th>Dados Financeiros</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>
                                    <strong>{{ $user->name }}</strong>
                                    @if($user->email_verified_at)
                                        <i class="bi bi-patch-check-fill text-success" title="Email verificado"></i>
                                    @endif
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($user->financas_count > 0)
                                        <span class="badge bg-success">
                                            <i class="bi bi-check"></i> Possui dados
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-x"></i> Sem dados
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @if($user->financas_count > 0)
                                            <a href="{{ route('admin.users.finances', $user) }}" 
                                               class="btn btn-sm btn-info" title="Ver Dados Financeiros">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.users.edit', $user) }}" 
                                           class="btn btn-sm btn-warning" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($users->count() > 1)
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    title="Deletar" onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-people" style="font-size: 4rem; color: #ccc;"></i>
                <h4 class="mt-3">Nenhum usuário encontrado</h4>
                <p class="text-muted">Comece criando o primeiro usuário.</p>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="bi bi-person-plus"></i> Criar Primeiro Usuário
                </a>
            </div>
        @endif
    </div>
</div>

<!-- API Info -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="bi bi-info-circle"></i> Informações da API
                </h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td><strong>URL Base:</strong></td>
                        <td><code>{{ url('/api') }}</code></td>
                    </tr>
                    <tr>
                        <td><strong>Documentação:</strong></td>
                        <td><a href="/API_DOCUMENTATION.md" target="_blank">Ver Docs</a></td>
                    </tr>
                    <tr>
                        <td><strong>Versão:</strong></td>
                        <td>1.0.0</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="bi bi-terminal"></i> Teste Rápido
                </h6>
            </div>
            <div class="card-body">
                <p class="small text-muted">Teste a API com cURL:</p>
                <code class="small">
                    curl -X POST {{ url('/api/login') }} \<br>
                    &nbsp;&nbsp;-H "Content-Type: application/json" \<br>
                    &nbsp;&nbsp;-d '{"email":"admin@financas.com","password":"123456"}'
                </code>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja deletar o usuário <strong id="userName"></strong>?</p>
                <p class="text-danger small">
                    <i class="bi bi-exclamation-triangle"></i>
                    Esta ação não pode ser desfeita e todos os dados financeiros do usuário serão perdidos.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Deletar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function confirmDelete(userId, userName) {
    document.getElementById('userName').textContent = userName;
    document.getElementById('deleteForm').action = `/admin/users/${userId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endsection
