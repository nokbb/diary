<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Models\Memory;

class DeleteOrphanImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:delete-orphan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete orphaned images not linked in the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // 画像が保存されているディレクトリのパス
        $directoryPath = storage_path('app/public/uploads');

        // すべての画像ファイルを取得
        $allFiles = File::files($directoryPath);

        foreach ($allFiles as $file) {
            // データベースに存在するかを確認
            $fileName = $file->getFilename();
            $exists = Memory::where('file_path', $fileName)->exists();

            // データベースに存在しない場合、画像を削除
            if (!$exists) {
                File::delete($file->getPathname());
                $this->info("Deleted orphaned image: " . $fileName);
            }
        }

        $this->info("Orphaned image deletion process complete.");
        return 0;
    }
}
