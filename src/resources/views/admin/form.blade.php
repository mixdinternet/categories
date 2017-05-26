@extends('mixdinternet/admix::form')

@section('title')
    Gerenciar categorias
@endsection

@section('form')
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs pull-right">
            <li class="active"><a href="#tab_geral" data-toggle="tab">Geral</a></li>
        </ul>
        {!! BootForm::horizontal(['model' => $category, 'store' => 'admin.' . $categoryType . '.categories.store', 'update' => 'admin.' . $categoryType . '.categories.update'
            , 'id' => 'form-model', 'class' => 'form-horizontal form-rocket jq-form-validate jq-form-save'
            , 'files' => true ]) !!}
        <div class="tab-content">
            <div class="tab-pane active" id="tab_geral">
                <div class="row">
                    <div class="col-md-10">
                        <div class="tab-content">
                            @if ($category->id)
                                {!! BootForm::text('id', 'Código', $category->id, ['disabled' => true]) !!}
                            @endif

                            {!! BootForm::select('status', 'Status', ['active' => 'Ativo', 'inactive' => 'Inativo'], null
                                , ['class' => 'jq-select2', 'data-rule-required' => true]) !!}

                            {!! BootForm::select('star', 'Destaque', ['0' => 'Não', '1' => 'Sim'], null
                                , ['class' => 'jq-select2', 'data-rule-required' => true]) !!}

                            {!! BootForm::text('name', 'Nome', null, ['data-rule-required' => true, 'maxlength' => '150']) !!}

                            @if (config('mcategories.image', true))
                                {!! BootForm::file('image', 'Imagem', [
                                        'data-allowed-file-extensions' => '["jpg", "png"]',
                                        'data-initial-preview' => '["<img src=\"' . $category->image->url('crop') . '\" class=\"file-preview-image\">"]',
                                        'data-initial-caption' => $category->image->originalFilename(),
                                        'data-min-image-width' => config('mcategories.image.width', 640),
                                        'data-min-image-height' => config('mcategories.image.height', 480),
                                        'data-aspect-ratio' => number_format(config('mcategories.image.width', 640)/config('mcategories.image.height', 480), 2)
                                    ]) !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {!! BootForm::close() !!}
    </div>
@endsection