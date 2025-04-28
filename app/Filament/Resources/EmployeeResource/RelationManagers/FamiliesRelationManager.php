<?php

namespace App\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FamiliesRelationManager extends RelationManager
{
    protected static string $relationship = 'families';

    public function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Select::make('hubungan')
                ->options([
                    'ayah' => 'Ayah',
                    'ibu' => 'Ibu',
                    'suami/istri' => 'Suami/Istri',
                    'anak' => 'Anak',
                    'saudara' => 'Saudara',
                    'lainnya' => 'Lainnya'
                ])
                ->required()
                ->columnSpan(2),
                
            Forms\Components\TextInput::make('nama')
                ->required()
                ->columnSpan(3),
                
            Forms\Components\TextInput::make('tempat_lahir')
                ->columnSpan(2),
                
            Forms\Components\DatePicker::make('tanggal_lahir')
                ->columnSpan(2),
                
            Forms\Components\TextInput::make('pendidikan')
                ->columnSpan(2),
                
            Forms\Components\TextInput::make('alamat')
                ->columnSpanFull(),
                
            Forms\Components\TextInput::make('pekerjaan')
                ->columnSpan(3),
                
            Forms\Components\TextInput::make('no_telepon')
                ->tel()
                ->columnSpan(2),
                
            Forms\Components\Toggle::make('emergency_contact')
                ->label('Kontak Darurat?')
                ->onColor('danger')
                ->offColor('gray'),
        ])
        ->columns(5);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama')
            ->columns([
                Tables\Columns\TextColumn::make('hubungan')
                ->label('Hubungan')
                ->sortable(),
                
            Tables\Columns\TextColumn::make('nama')
                ->searchable(),
                
            Tables\Columns\TextColumn::make('pekerjaan'),
                
            Tables\Columns\TextColumn::make('no_telepon'),
                
            Tables\Columns\IconColumn::make('emergency_contact')
                ->label('Darurat')
                ->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tambah Anggota Keluarga'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make() // Tambahkan aksi View
                ->label('Lihat Detail'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                
            ])
            //->defaultSort('type');

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                
            ]);
    }
}
