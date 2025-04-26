<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EducationsRelationManager extends RelationManager
{
    protected static string $relationship = 'educations';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('tingkat')
                ->options([
                    'Dasar' => 'Dasar',
                    'Lanjutan Pertama' => 'Lanjutan Pertama',
                    'Lanjutan Atas' => 'Lanjutan Atas',
                    'Akademi' => 'Akademi',
                    'Universitas' => 'Universitas',
                    'Kursus-Kursus' => 'Kursus-Kursus',
                    'Lain-Lain' => 'Lain-Lain'
                ])
                ->required()
                ->columnSpan(2),
                
            Forms\Components\TextInput::make('nama_sekolah')
                ->required()
                ->columnSpan(3),
                
            Forms\Components\TextInput::make('tempat')
                ->required()
                ->columnSpan(3),
                
            Forms\Components\TextInput::make('tahun_lulus')
                ->numeric()
                ->minValue(1900)
                ->maxValue(now()->year)
                ->required()
                ->columnSpan(2),
                
            Forms\Components\TextInput::make('jurusan')
                ->columnSpan(3),
                
            Forms\Components\Textarea::make('keterangan')
                ->columnSpanFull(),
                
            Forms\Components\Toggle::make('sedang_ditempuh')
                ->label('Sedang Ditempuh?')
                ->onColor('success')
                ->offColor('danger')
                ->reactive()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('education')
            ->columns([
                Tables\Columns\TextColumn::make('tingkat')
                ->sortable()
                ->searchable(),
                
            Tables\Columns\TextColumn::make('nama_sekolah')
                ->searchable(),
                
            Tables\Columns\TextColumn::make('tempat'),
                
            Tables\Columns\TextColumn::make('tahun_lulus')
                ->sortable(),
                
            Tables\Columns\TextColumn::make('jurusan'),
                
            Tables\Columns\IconColumn::make('sedang_ditempuh')
                ->boolean()
                ->label('Status')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->label('Tambah Pendidikan'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                
            ])
            
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
