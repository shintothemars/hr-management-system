<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WorkExperiencesRelationManager extends RelationManager
{
    protected static string $relationship = 'workExperiences';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_perusahaan')
                ->required()
                ->columnSpan(2),
                
            Forms\Components\TextInput::make('jabatan')
                ->required()
                ->columnSpan(2),
                
            Forms\Components\DatePicker::make('tanggal_mulai')
                ->required()
                ->columnSpan(1),
                
            Forms\Components\DatePicker::make('tanggal_selesai')
                ->columnSpan(1),
                
            Forms\Components\TextInput::make('gaji')
                ->numeric()
                ->prefix('Rp')
                ->columnSpan(1),
                
            Forms\Components\Textarea::make('alasan_keluar')
                ->columnSpanFull()
                ->maxLength(500),
        ])
        ->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama perusahaan')
            ->columns([
                Tables\Columns\TextColumn::make('nama_perusahaan')
                ->searchable(),
                
            Tables\Columns\TextColumn::make('jabatan'),
                
            Tables\Columns\TextColumn::make('tanggal_mulai')
                ->date('d/m/Y'),
                
            Tables\Columns\TextColumn::make('tanggal_selesai')
                ->date('d/m/Y'),
                
            Tables\Columns\TextColumn::make('gaji')
                ->money('IDR'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->label('Tambah Pengalaman'),
                
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                
                
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('tanggal_mulai', 'desc');
    }
        protected function beforeCreate(): void
    {
        Notification::make()
            ->title('Pengalaman kerja berhasil ditambahkan')
            ->success()
            ->send();
    }
}
