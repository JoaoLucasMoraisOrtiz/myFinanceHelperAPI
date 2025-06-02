@extends('layouts.admin')

@section('title', 'Dados Financeiros')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Dados Financeiros - {{ $user->name }}</h1>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Voltar
    </a>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Informações do Usuário</h5>
                <p class="mb-1"><strong>Nome:</strong> {{ $user->name }}</p>
                <p class="mb-1"><strong>Email:</strong> {{ $user->email }}</p>
                <p class="mb-0"><strong>Último acesso:</strong> {{ $user->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Resumo Financeiro</h5>
                @php
                    $totalReceitas = collect($dados['receitas'])->sum('valor');
                    $totalDespesas = collect($dados['despesas'])->sum('valor');
                    $saldo = $totalReceitas - $totalDespesas;
                @endphp
                <p class="mb-1"><strong>Total Receitas:</strong> <span class="text-success">R$ {{ number_format($totalReceitas, 2, ',', '.') }}</span></p>
                <p class="mb-1"><strong>Total Despesas:</strong> <span class="text-danger">R$ {{ number_format($totalDespesas, 2, ',', '.') }}</span></p>
                <p class="mb-0"><strong>Saldo:</strong> 
                    <span class="{{ $saldo >= 0 ? 'text-success' : 'text-danger' }}">
                        R$ {{ number_format($saldo, 2, ',', '.') }}
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Receitas -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-plus-circle text-success"></i> Receitas ({{ count($dados['receitas']) }})
                </h5>
            </div>
            <div class="card-body">
                @if(empty($dados['receitas']))
                    <p class="text-muted">Nenhuma receita cadastrada.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Descrição</th>
                                    <th>Valor</th>
                                    <th>Categoria</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dados['receitas'] as $receita)
                                <tr>
                                    <td>{{ $receita['descricao'] ?? 'N/A' }}</td>
                                    <td class="text-success">R$ {{ number_format($receita['valor'] ?? 0, 2, ',', '.') }}</td>
                                    <td>{{ $receita['categoria'] ?? 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Despesas -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-minus-circle text-danger"></i> Despesas ({{ count($dados['despesas']) }})
                </h5>
            </div>
            <div class="card-body">
                @if(empty($dados['despesas']))
                    <p class="text-muted">Nenhuma despesa cadastrada.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Descrição</th>
                                    <th>Valor</th>
                                    <th>Categoria</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dados['despesas'] as $despesa)
                                <tr>
                                    <td>{{ $despesa['descricao'] ?? 'N/A' }}</td>
                                    <td class="text-danger">R$ {{ number_format($despesa['valor'] ?? 0, 2, ',', '.') }}</td>
                                    <td>{{ $despesa['categoria'] ?? 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Planejamento -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar-alt text-info"></i> Planejamento
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Planejamento Receitas -->
                    <div class="col-md-6">
                        <h6 class="text-success">Receitas Planejadas</h6>
                        @if(empty($dados['planejamento']['receitas']))
                            <p class="text-muted">Nenhuma receita planejada.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Descrição</th>
                                            <th>Valor</th>
                                            <th>Categoria</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dados['planejamento']['receitas'] as $receita)
                                        <tr>
                                            <td>{{ $receita['descricao'] ?? 'N/A' }}</td>
                                            <td class="text-success">R$ {{ number_format($receita['valor'] ?? 0, 2, ',', '.') }}</td>
                                            <td>{{ $receita['categoria'] ?? 'N/A' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <!-- Planejamento Despesas -->
                    <div class="col-md-6">
                        <h6 class="text-danger">Despesas Planejadas</h6>
                        @if(empty($dados['planejamento']['despesas']))
                            <p class="text-muted">Nenhuma despesa planejada.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Descrição</th>
                                            <th>Valor</th>
                                            <th>Categoria</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dados['planejamento']['despesas'] as $despesa)
                                        <tr>
                                            <td>{{ $despesa['descricao'] ?? 'N/A' }}</td>
                                            <td class="text-danger">R$ {{ number_format($despesa['valor'] ?? 0, 2, ',', '.') }}</td>
                                            <td>{{ $despesa['categoria'] ?? 'N/A' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dados Brutos JSON (para debug) -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-code text-secondary"></i> Dados Brutos (JSON)
                </h5>
            </div>
            <div class="card-body">
                <pre class="bg-light p-3 rounded" style="max-height: 300px; overflow-y: auto;"><code>{{ json_encode($dados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
            </div>
        </div>
    </div>
</div>
@endsection
