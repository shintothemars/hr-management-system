<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReferencesRelationManager extends RelationManager
{
    protected static string $relationship = 'references';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                ->required()
                ->columnSpan(2),
                
            Forms\Components\TextInput::make('hubungan')
                ->required()
                ->columnSpan(2)
                ->placeholder('Contoh: Mantan Atasan, Rekan Kerja'),
                
            Forms\Components\Textarea::make('alamat')
                ->required()
                ->columnSpanFull(),
                
            Forms\Components\TextInput::make('telepon')
                ->tel()
                ->required()
                ->columnSpan(2),
                
                Forms\Components\TextInput::make('pekerjaan_jabatan')
                ->required()
                ->label('Pekerjaan/Jabatan')
                ->columnSpan(2),
                
            Forms\Components\Textarea::make('keterangan')
                ->columnSpanFull(),
        ])
        ->columns(4);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama')
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                ->searchable(),
                
                Tables\Columns\TextColumn::make('hubungan'),
                    
                Tables\Columns\TextColumn::make('pekerjaan_jabatan')
                    ->label('Pekerjaan/Jabatan'),
                
            Tables\Columns\TextColumn::make('telepon'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->label('Tambah Referensi'),
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
