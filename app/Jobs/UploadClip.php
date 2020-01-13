<?php

namespace App\Jobs;

use App\EpisodeSellCourse;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class UploadClip implements ShouldQueue
{
  use InteractsWithQueue, Queueable, SerializesModels;

  protected $episode;
  protected $path;
  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct($episode, $file_path)
  {
    $this->episode = $episode;
    $this->path = $file_path;
  }

  public function handle()
  {
    Log::info('Start UploadClip Job');
    $this->episode->update(['transcode_status' => 'transfering']);
    $client = new Client(['headers' => ['Authorization' => 'Bearer 4styVfeWRWtLEqZYu5v53me1qIODJ6W6RLwkiPQVFPxwdxGHb1oHC2hHwArF']]);
    try {
      $source = Storage::disk('local')->readStream($this->path);
      if ($this->episode->type == 'preview') {
        $params = [
          ['name' => 'service',       'contents' => 'tutorme_clear'],
          ['name' => 'source',        'contents' => $source],
          ['name' => 'priority',      'contents' => 'default'],
          ['name' => 'callback_url',  'contents' => 'https://bo.tutorme.in.th/api/course/transcode/callback']
        ];
      } else if (in_array($this->episode->type, ['free', 'payment'])) {
        $content_id = $this->episode->content_id;
        $params = [
          ['name' => 'service',       'contents' => 'tutorme_drm'],
          ['name' => 'source',        'contents' => $source],
          ['name' => 'priority',      'contents' => 'default'],
          ['name' => 'callback_url',  'contents' => 'https://bo.tutorme.in.th/api/course/transcode/callback'],
          ['name' => 'content_id',    'contents' => $content_id]
        ];
      }

      $transcode_url = 'http://upload-seeme.mthai.com/api/transcode';
      $res = $client->request('POST', $transcode_url, [
        'multipart' => $params,
        'verify' => (config('app.env') === 'local') ? false : true
      ]);
      $status_code = $res->getStatusCode();
      $body = $res->getBody();

      if ($status_code == 200) {
          $data = json_decode($body, true);
          if (is_array($data)) {
            $this->episode->task_id = $data['id'];
            $streams = [];
            foreach ($data['qualities'] as $key => $value) {
              $streams[$key] = ['quality' => $value];
            }
            $this->episode->streams = $streams;
            $this->episode->meta = $data['meta'];
            $this->episode->duration = array_get($data, 'meta.format.duration', 0);
            $this->episode->transcode_status = 'queued';
            $this->episode->save();

            $disk = Storage::disk('local');
            if ($disk->exists($this->path)) {
              $disk->delete($this->path);
            }
          } else {
            Log::info($body);
            Log::error('[Error] Failed uploading clip id = '.(string)$this->episode->_id.' / Status = '.$status_code);
            $this->episode->transcode_status = 'failed';
            $this->episode->save();
          }
      } else {
        Log::error('[Cannot upload] Failed uploading clip id = '.(string)$this->episode->_id.' / Status = '.$status_code);
        $this->episode->transcode_status = 'failed';
        $this->episode->save();
      }
    } catch (GuzzleException $e) {
      Log::error($e->getMessage());
      $this->episode->transcode_status = 'failed';
      $this->episode->save();
    }
  }
}
