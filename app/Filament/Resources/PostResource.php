<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('slug_overridden')
                    ->default(false)
                    ->dehydrated(false),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function(Get $get, Set $set, $state) {
                        if(! $get('slug_overridden') && filled($state)) {
                            $set('slug', Str::slug($state));
                        }
                    }),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function(Get $get, Set $set, $state) {
                        $set('slug_overridden', !empty($state));
                        if(empty($state)) {
                            $set('slug', Str::slug($get('title')));
                        }
                    }),
                TiptapEditor::make('content')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    TextColumn::make('title')
                        ->searchable()
                        ->sortable(),
                    TextColumn::make('slug')
                        ->searchable()
                        ->badge()
                        ->color('gray')
                        ->formatStateUsing(fn ($state): string => 'Permalink: ' . $state),
                ])
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('visit_page')
                    ->icon('heroicon-m-link')
                    ->url(fn(Post $post) => $post->slug),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
