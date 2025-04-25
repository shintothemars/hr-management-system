<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartmentResource\Pages;
use App\Filament\Resources\DepartmentResource\RelationManagers;
use App\Models\Department;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                ->required()
                ->maxLength(255),
                Forms\Components\Textarea::make('deskripsi')
                ->maxLength(65535),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                ->label('Nama Departemen')
                ->searchable()
                ->sortable(),
                
            Tables\Columns\TextColumn::make('deskripsi')
                ->label('Deskripsi')
                ->limit(50)
                ->tooltip(function (TextColumn $column): ?string {
                    $state = $column->getState();
                    return strlen($state) > 50 ? $state : null;
                }),
                
            Tables\Columns\TextColumn::make('created_at')
                ->label('Dibuat Pada')
                ->dateTime('d/m/Y H:i')
                ->sortable(),
                
            Tables\Columns\TextColumn::make('employees_count')
                ->label('Jumlah Karyawan')
                ->counts('employees')
                ->sortable(),                
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('nama')
                ->options(function () {
                    return Department::pluck('nama', 'nama')->toArray();
                })
                ->label('Filter by Department'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->icon('heroicon-o-pencil'),
                
                Tables\Actions\ViewAction::make()
                ->icon('heroicon-o-eye'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
                ])
                ->emptyStateHeading('Belum ada departemen')
                ->emptyStateDescription('Klik tombol dibawah untuk membuat departemen baru')
                ->emptyStateActions([
                    Tables\Actions\CreateAction::make()
                        ->label('Buat Departemen Baru')
                        ->icon('heroicon-o-plus'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDepartments::route('/'),
            'create' => Pages\CreateDepartment::route('/create'),
            'edit' => Pages\EditDepartment::route('/{record}/edit'),
        ];
    }
}
