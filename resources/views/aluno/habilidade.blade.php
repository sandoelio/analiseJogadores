@extends('layouts.app')

@section('title', 'Analise de Desempenhos')

@push('styles')
<style>
.habilidade-shell{max-width:1100px;margin:0 auto;padding:.95rem 0 1.2rem}.habilidade-topo{display:flex;align-items:stretch;justify-content:space-between;gap:1rem;margin-bottom:1rem}.habilidade-heading,.habilidade-voltar-wrap,.habilidade-card{border:1px solid #dbe1ec;border-radius:1rem;background:#fff;box-shadow:0 6px 18px rgba(26,42,80,.08)}.habilidade-heading{flex:1 1 auto;padding:1rem 1.1rem}.habilidade-heading-top{display:flex;align-items:center;gap:.7rem;flex-wrap:wrap}.habilidade-chip{display:inline-flex;align-items:center;gap:.4rem;padding:.3rem .65rem;border-radius:999px;background:#eef3fb;color:#28365F;font-size:.76rem;font-weight:700;text-transform:uppercase;letter-spacing:.02em}.habilidade-texto-topo{margin:0;color:#5f6b85;font-size:.9rem;line-height:1.45}.habilidade-title{margin:.45rem 0 0;color:#1f2d4f;font-size:1.46rem;font-weight:700;line-height:1.2}.habilidade-voltar-wrap{flex:0 0 180px;display:flex;align-items:center;justify-content:center;padding:.9rem}.habilidade-voltar{width:100%;min-height:42px;display:inline-flex;align-items:center;justify-content:center;border-radius:.85rem;font-weight:700}.habilidade-card{overflow:hidden}.habilidade-card .card-header{padding:1rem 1.1rem;background:linear-gradient(135deg,#28365F 0%,#40548c 100%)!important;color:#fff}.habilidade-card .card-header h5{margin:0;font-size:1.08rem;font-weight:700}.habilidade-card .card-body{padding:1.15rem}.habilidade-card .form-label{color:#33405f;font-weight:600}.habilidade-card .form-control,.habilidade-card .form-select{min-height:46px;border-radius:.8rem;border-color:#dbe1ec;box-shadow:none}.habilidade-card .form-control:focus,.habilidade-card .form-select:focus{border-color:#8ea3ce;box-shadow:0 0 0 .2rem rgba(40,54,95,.12)}.habilidade-card .form-check-input:checked{background-color:#28365F;border-color:#28365F}.habilidade-card .nav-tabs{border-bottom:1px solid #dbe1ec;gap:.35rem}.habilidade-card .nav-tabs .nav-link{border:1px solid #dbe1ec;border-bottom:none;border-top-left-radius:.85rem;border-top-right-radius:.85rem;background:#edf2f8;color:#2a3b5f;font-weight:700;font-size:.9rem}.habilidade-card .nav-tabs .nav-link.active{color:#fff;background:#28365F;border-color:#28365F}.habilidade-card .tab-content{padding-top:.3rem}.habilidade-card .tab-pane{min-height:260px}.habilidade-actions{display:flex;align-items:center;justify-content:space-between;gap:.8rem;margin-top:1.1rem}.habilidade-actions .btn{min-height:44px;border-radius:.85rem;font-weight:700}.btn-navbar-blue{background:#28365F;border-color:#28365F;color:#fff}.btn-navbar-blue:hover,.btn-navbar-blue:focus{background:#1f2d4f;border-color:#1f2d4f;color:#fff}input[readonly],select[disabled]{background-color:#e9ecef;opacity:1;cursor:not-allowed}html,body{overflow-x:hidden}.saude-item{align-items:center;padding:.75rem .85rem;border:1px solid #e4eaf3;border-radius:.9rem;background:#fbfcfe}.saude-item label{margin-bottom:0;font-weight:600;flex:1}.saude-radios{display:flex;gap:1rem;flex-shrink:0}@media (max-width:576px){.habilidade-shell{padding-top:.5rem}.habilidade-topo{flex-direction:column;gap:.75rem}.habilidade-voltar-wrap{flex-basis:auto;padding:.8rem}.habilidade-title{font-size:1.2rem}.habilidade-card .card-body{padding:.95rem}.habilidade-card .nav-tabs{flex-wrap:nowrap;overflow-x:auto;overflow-y:hidden;padding-bottom:.15rem}.habilidade-card .nav-tabs .nav-link{white-space:nowrap;font-size:.86rem}.habilidade-card .tab-pane{min-height:0}.habilidade-actions{flex-direction:column;align-items:stretch}.saude-item{flex-direction:column;align-items:flex-start;gap:.7rem}}
</style>
@endpush

@section('content')
@php
    $alunosOrdenados=$alunos->sortByDesc(function($a){if(isset($a->idade)&&$a->idade!==null){return $a->idade;}if(!empty($a->data_nascimento)){return \Carbon\Carbon::parse($a->data_nascimento)->age;}return -1;})->values();
@endphp
<div class="container-fluid habilidade-shell">
    <div class="habilidade-topo">
        <div class="habilidade-heading">
            <div class="habilidade-heading-top">
                <span class="habilidade-chip"><i class="bi bi-arrow-repeat"></i>Atualizacao</span>
                <p class="habilidade-texto-topo">Selecione o atleta, revise a identificacao e registre a nova atualizacao nas abas abaixo.</p>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="habilidade-card card mb-4">
                <div class="card-header text-center"><h5 class="mb-0">Atualizar Atleta</h5></div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('aluno.habilidade.update') }}" method="POST">
                        @csrf
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#aba1" role="tab">Identificacao</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#aba2" role="tab">Tecnicas</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#aba3" role="tab">Fisicos</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#aba4" role="tab">Corporal</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#aba5" role="tab">Perguntas</a></li>
                        </ul>

                        <div class="tab-content mt-3">
                            <div class="tab-pane fade show active" id="aba1" role="tabpanel">
                                <div class="mb-3">
                                    <label for="aluno_select" class="form-label">Selecione o Atleta</label>
                                    <select id="aluno_select" name="aluno_id" class="form-select" required>
                                        <option value="" disabled {{ old('aluno_id') ? '' : 'selected' }}>-- selecione --</option>
                                        @foreach ($alunosOrdenados as $a)
                                            @php
                                                $idadeExib=$a->idade??(!empty($a->data_nascimento)?\Carbon\Carbon::parse($a->data_nascimento)->age:null);
                                                $selected=(string) old('aluno_id') === (string) $a->id ? 'selected' : '';
                                                $dataNasc=$a->data_nascimento?$a->data_nascimento->toDateString():'';
                                                $sexoVal=$a->sexo ?? '';
                                                $telefoneVal=$a->telefone ?? '';
                                                $idadeVal=$a->idade??($a->data_nascimento?\Carbon\Carbon::parse($a->data_nascimento)->age:'');
                                            @endphp
                                            <option value="{{ $a->id }}" data-datanascimento="{{ $dataNasc }}" data-sexo="{{ $sexoVal }}" data-idade="{{ $idadeVal }}" data-telefone="{{ $telefoneVal }}" {{ $selected }}>
                                                {{ $a->nome }}@if ($idadeExib) ({{ $idadeExib }} anos) @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="row g-3 align-items-end">
                                    <div class="col-12 col-lg-4">
                                        <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                                        <input type="date" id="data_nascimento" name="data_nascimento" class="form-control" value="{{ old('data_nascimento') }}">
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-3">
                                        <label class="form-label">Sexo</label>
                                        <div class="d-flex">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="sexo" id="sexo_m" value="Masculino" {{ old('sexo') == 'Masculino' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="sexo_m">M</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="sexo" id="sexo_f" value="Feminino" {{ old('sexo') == 'Feminino' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="sexo_f">F</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-2">
                                        <label for="idade_display" class="form-label">Idade</label>
                                        <input type="text" id="idade_display" class="form-control" value="{{ old('idade') }}" readonly>
                                        <input type="hidden" id="idade" name="idade" value="{{ old('idade') }}">
                                    </div>
                                    <div class="col-12 col-lg-3">
                                        <label for="telefone" class="form-label">Telefone</label>
                                        <input type="text" id="telefone" name="telefone" placeholder="(00) 00000-0000" class="form-control @error('telefone') is-invalid @enderror" value="{{ old('telefone') }}" inputmode="numeric" maxlength="15">
                                        @error('telefone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                @error('sexo')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="tab-pane fade" id="aba2" role="tabpanel">
                                <div class="row g-3">
                                    @foreach (['arremesso', 'passe', 'marcacao', 'bandeja', 'rebote', 'dominio'] as $campo)
                                        <div class="col-6">
                                            <label for="{{ $campo }}" class="form-label">{{ ucfirst($campo === 'dominio' ? 'Dominio de Bola' : $campo) }}</label>
                                            <input type="number" id="{{ $campo }}" name="{{ $campo }}" class="form-control" value="" min="0" max="10" step="1" readonly>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="tab-pane fade" id="aba3" role="tabpanel">
                                <div class="row g-3">
                                    @foreach (['potencia_mmss' => 'Potencia MMSS', 'capacidade_aerobica' => 'Capacidade Aerobica', 'agilidade' => 'Agilidade (s)', 'flexibilidade' => 'Flexibilidade', 'potencia_mmii' => 'Potencia MMII', 'envergadura_cm' => 'Envergadura (cm)'] as $campo => $label)
                                        <div class="col-6">
                                            <label for="{{ $campo }}" class="form-label">{{ $label }}</label>
                                            <input type="number" id="{{ $campo }}" name="{{ $campo }}" class="form-control" value="" min="0" step="any" readonly>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="tab-pane fade" id="aba4" role="tabpanel">
                                <div class="row g-3">
                                    @foreach (['massa_corporal_kg' => 'Massa Corporal (kg)', 'gordura_pct' => 'Gordura (%)', 'massa_magra_pct' => 'Massa Magra (%)', 'imc' => 'IMC'] as $campo => $label)
                                        <div class="col-6">
                                            <label for="{{ $campo }}" class="form-label">{{ $label }}</label>
                                            <input type="number" id="{{ $campo }}" name="{{ $campo }}" class="form-control" value="" min="0" step="any" readonly>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="tab-pane fade" id="aba5" role="tabpanel">
                                <div class="row g-3">
                                    <div class="col-12 mt-2"><h6 class="text-primary">Informacoes de Saude</h6></div>
                                    @php $saudeCampos=['problema_saude'=>'Possui problema de saude?','atestado_valido'=>'Esta com atestado valido?','usa_medicacao'=>'Faz uso de medicacao?']; @endphp
                                    @foreach ($saudeCampos as $campo => $label)
                                        <div class="col-12 saude-item">
                                            <label for="{{ $campo }}_sim">{{ $label }}</label>
                                            <div class="saude-radios">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="{{ $campo }}" id="{{ $campo }}_sim" value="1" {{ old($campo) === '1' ? 'checked' : '' }} disabled>
                                                    <label class="form-check-label" for="{{ $campo }}_sim">Sim</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="{{ $campo }}" id="{{ $campo }}_nao" value="0" {{ old($campo) === '0' ? 'checked' : '' }} disabled>
                                                    <label class="form-check-label" for="{{ $campo }}_nao">Nao</label>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($campo === 'problema_saude')
                                            <div class="col-12 d-none" id="grupo_problema_saude_descricao">
                                                <label for="problema_saude_descricao" class="form-label">Qual tipo?</label>
                                                <input type="text" id="problema_saude_descricao" name="problema_saude_descricao" class="form-control @error('problema_saude_descricao') is-invalid @enderror" value="{{ old('problema_saude_descricao') }}" placeholder="Descreva o problema de saude">
                                                @error('problema_saude_descricao')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endif

                                        @if ($campo === 'atestado_valido')
                                            <div class="col-12 d-none" id="grupo_data_atestado">
                                                <label for="data_atestado" class="form-label">Data do atestado</label>
                                                <input type="date" id="data_atestado" name="data_atestado" class="form-control @error('data_atestado') is-invalid @enderror" value="{{ old('data_atestado') }}">
                                                @error('data_atestado')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="habilidade-actions">
                            <a href="{{ route('tecnico.dashboard') }}" class="btn btn-outline-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-navbar-blue">Atualizar Atleta</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
window.routes={lastAnalysis:"{{ route('aluno.lastAnalysis', ['aluno' => '__ID__']) }}"};
document.addEventListener('DOMContentLoaded',()=>{const select=document.getElementById('aluno_select');if(!select)return;const tecnicos=['arremesso','passe','marcacao','bandeja','rebote','dominio'];const fisicos=['potencia_mmss','capacidade_aerobica','agilidade','flexibilidade','potencia_mmii','envergadura_cm'];const composicao=['massa_corporal_kg','gordura_pct','massa_magra_pct','imc'];const saude=['problema_saude','atestado_valido','usa_medicacao'];const dataNascEl=document.getElementById('data_nascimento');const sexoMEl=document.getElementById('sexo_m');const sexoFEl=document.getElementById('sexo_f');const idadeDisplayEl=document.getElementById('idade_display');const idadeHiddenEl=document.getElementById('idade');const telefoneEl=document.getElementById('telefone');const problemaSaudeDescricaoEl=document.getElementById('problema_saude_descricao');const grupoProblemaSaudeDescricaoEl=document.getElementById('grupo_problema_saude_descricao');const dataAtestadoEl=document.getElementById('data_atestado');const grupoDataAtestadoEl=document.getElementById('grupo_data_atestado');function setReadOnlyByIds(ids,ro=true){ids.forEach(id=>{const el=document.getElementById(id);if(!el)return;el.readOnly=!!ro;el.disabled=false;});}function setDisabledRadiosByNames(names,dis=true){names.forEach(name=>{document.querySelectorAll(`input[name="${name}"]`).forEach(r=>{r.disabled=!!dis;if(dis)r.checked=false;});});}function mostrarCampoCondicional(grupoEl,inputEl,mostrar){if(!grupoEl||!inputEl)return;grupoEl.classList.toggle('d-none',!mostrar);if(!mostrar)inputEl.value='';}function atualizarCamposCondicionaisSaude(){mostrarCampoCondicional(grupoProblemaSaudeDescricaoEl,problemaSaudeDescricaoEl,!!document.getElementById('problema_saude_sim')?.checked);mostrarCampoCondicional(grupoDataAtestadoEl,dataAtestadoEl,!!document.getElementById('atestado_valido_sim')?.checked);}setReadOnlyByIds([...tecnicos,...fisicos,...composicao],true);setDisabledRadiosByNames(saude,true);function aplicarMascaraTelefone(valor){const digitos=(valor||'').replace(/\D/g,'').slice(0,11);if(digitos.length<=2)return digitos;const padrao=digitos.length>10?/(\d{2})(\d{0,5})(\d{0,4})/:/(\d{2})(\d{0,4})(\d{0,4})/;return digitos.replace(padrao,(_,ddd,parte1,parte2)=>{let telefone='('+ddd+')';if(parte1)telefone+=' '+parte1;if(parte2)telefone+='-'+parte2;return telefone;});}function preencherIdentificacao(id={}){if(dataNascEl)dataNascEl.value=id.data_nascimento??'';const sexoVal=(id.sexo??'').toString();if(sexoMEl)sexoMEl.checked=(sexoVal==='M'||sexoVal.toLowerCase()==='masculino');if(sexoFEl)sexoFEl.checked=(sexoVal==='F'||sexoVal.toLowerCase()==='feminino');if(idadeDisplayEl){if(id.idade!==undefined&&id.idade!==null&&id.idade!==''){idadeDisplayEl.value=id.idade;if(idadeHiddenEl)idadeHiddenEl.value=id.idade;}else{idadeDisplayEl.value='';if(idadeHiddenEl)idadeHiddenEl.value='';}}if(telefoneEl)telefoneEl.value=aplicarMascaraTelefone(id.telefone??'');}function normalizeAnalisePayload(payload){if(!payload)return{};const campos=['arremesso','passe','marcacao','bandeja','rebote','dominio','potencia_mmss','capacidade_aerobica','agilidade','flexibilidade','potencia_mmii','envergadura_cm','massa_corporal_kg','gordura_pct','massa_magra_pct','imc','problema_saude','problema_saude_descricao','atestado_valido','data_atestado','usa_medicacao'];const fromGroups={};if(payload.tecnicos||payload.fisicos||payload.composicao||payload.saude){Object.assign(fromGroups,payload.tecnicos||{},payload.fisicos||{},payload.composicao||{},payload.saude||{});}const top={};campos.forEach(k=>{if(payload[k]!==undefined)top[k]=payload[k];});return Object.assign({},top,fromGroups);}function preencherAnalise(payload){const normalized=normalizeAnalisePayload(payload);[tecnicos,fisicos,composicao].forEach(grupo=>grupo.forEach(campo=>{const el=document.getElementById(campo);if(!el)return;el.value=(normalized[campo]!==undefined&&normalized[campo]!==null)?normalized[campo]:'';el.readOnly=false;el.disabled=false;}));setDisabledRadiosByNames(saude,false);saude.forEach(name=>{const val=normalized[name];const sim=document.getElementById(`${name}_sim`);const nao=document.getElementById(`${name}_nao`);const isTrue=val===1||val==='1'||val===true||val==='true';const isFalse=val===0||val==='0'||val===false||val==='false';if(sim&&nao){sim.checked=!!isTrue;nao.checked=!!isFalse;}});if(problemaSaudeDescricaoEl)problemaSaudeDescricaoEl.value=normalized.problema_saude_descricao??'';if(dataAtestadoEl)dataAtestadoEl.value=normalized.data_atestado??'';atualizarCamposCondicionaisSaude();}function preencherFromOptionDataset(opt){if(!opt)return;const ds=opt.dataset||{};preencherIdentificacao({data_nascimento:ds.datanascimento||ds.data_nasc||ds.data_nascimento||'',sexo:ds.sexo||'',idade:ds.idade||ds.age||'',telefone:ds.telefone||''});}async function carregarAnaliseDoAluno(alunoId){if(!alunoId)return;const opt=select.options[select.selectedIndex];if(opt&&(opt.dataset&&(opt.dataset.datanascimento||opt.dataset.sexo||opt.dataset.idade||opt.dataset.telefone))){preencherFromOptionDataset(opt);}else{if(dataNascEl)dataNascEl.value='';if(sexoMEl)sexoMEl.checked=false;if(sexoFEl)sexoFEl.checked=false;if(idadeDisplayEl)idadeDisplayEl.value='';if(idadeHiddenEl)idadeHiddenEl.value='';if(telefoneEl)telefoneEl.value='';}const urlTemplate=window.routes&&window.routes.lastAnalysis?window.routes.lastAnalysis:null;if(!urlTemplate)return;const url=urlTemplate.replace('__ID__',encodeURIComponent(alunoId));try{const resp=await fetch(url,{credentials:'same-origin'});const payload=await resp.json();const idObj=payload.identificacao??{data_nascimento:payload.data_nascimento??null,sexo:payload.sexo??null,idade:payload.idade??null,telefone:payload.telefone??null};preencherIdentificacao(idObj);preencherAnalise(payload);}catch(err){console.error('Erro ao carregar ultima analise:',err);if(opt)preencherFromOptionDataset(opt);}}select.addEventListener('change',()=>{if(select.value)carregarAnaliseDoAluno(select.value);});const initialOpt=select.options[select.selectedIndex];if(initialOpt&&initialOpt.value!==''){setTimeout(()=>select.dispatchEvent(new Event('change',{bubbles:true})),50);}if(dataNascEl){dataNascEl.addEventListener('change',()=>{const val=dataNascEl.value;if(!val){if(idadeDisplayEl)idadeDisplayEl.value='';if(idadeHiddenEl)idadeHiddenEl.value='';return;}const hoje=new Date();const nasc=new Date(val+'T00:00:00');let idadeCalc=hoje.getFullYear()-nasc.getFullYear();const m=hoje.getMonth()-nasc.getMonth();if(m<0||(m===0&&hoje.getDate()<nasc.getDate()))idadeCalc--;if(idadeCalc>=0){if(idadeDisplayEl)idadeDisplayEl.value=idadeCalc+' anos';if(idadeHiddenEl)idadeHiddenEl.value=idadeCalc;}else{if(idadeDisplayEl)idadeDisplayEl.value='';if(idadeHiddenEl)idadeHiddenEl.value='';}});}['problema_saude','atestado_valido'].forEach(name=>{document.querySelectorAll(`input[name="${name}"]`).forEach(radio=>radio.addEventListener('change',atualizarCamposCondicionaisSaude));});atualizarCamposCondicionaisSaude();if(telefoneEl){telefoneEl.addEventListener('input',()=>{telefoneEl.value=aplicarMascaraTelefone(telefoneEl.value);});if(telefoneEl.value)telefoneEl.value=aplicarMascaraTelefone(telefoneEl.value);}const alert=document.querySelector('.alert-success');if(alert){const TIMEOUT=5000;if(!alert.classList.contains('fade'))alert.classList.add('fade','show');setTimeout(()=>{try{if(typeof bootstrap!=='undefined'&&bootstrap.Alert){bootstrap.Alert.getOrCreateInstance(alert).close();return;}}catch(e){}alert.style.transition='opacity 0.5s ease';alert.style.opacity='0';setTimeout(()=>{if(alert.parentNode)alert.parentNode.removeChild(alert);},500);},TIMEOUT);}});
</script>
@endpush
