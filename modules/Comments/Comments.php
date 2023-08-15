<?php

namespace Modules\Comments;

use Datainterface\Database;
use Datainterface\Insertion;
use Datainterface\MysqlDynamicTables;
use Datainterface\Query;
use Datainterface\Selection;

class Comments
{
    private int $error;

    /**
     * @return int
     */
    public function getError(): bool
    {
        return $this->error;
    }

    public function __construct(

      private readonly string $comment = "hello there",
      private readonly int $uid = 1,
      private readonly int $cid = 1,
      private readonly  int $type = 0,
      private readonly string $bundle = "movies",
      private readonly int $entityID = 0
  )
  {
      $this->schema();
      $this->error = false;
  }

  public function saveComment(): Comments
  {
      $comment['comment_body'] = $this->comment;
      $comment['comment_uid'] = $this->uid;
      $comment['bundle'] = $this->bundle;
      $comment['entity_id'] = $this->entityID;

      $this->error = empty(Insertion::insertRow('stream_comments', $comment));
      return $this;
  }


  public function saveReply(): Comments
  {
      $reply['comment_reply_body'] = $this->comment;
      $reply['comment_reply_uid'] = $this->uid;
      $reply['cid'] = $this->cid;
      $this->error = empty(Insertion::insertRow('stream_comments_reply', $reply));
      return $this;
  }

  public function saveLikes(): Comments
  {
      $type = $this->types($this->type);
      if($type === "comments"){
          $query = "SELECT * FROM stream_comments_likes WHERE cid = $this->cid AND comment_like_uid = $this->uid AND type = '$type'";
          $data = Query::query($query);

          if(!empty($data)){
              $query = "DELETE FROM stream_comments_likes WHERE cid = $this->cid AND comment_like_uid = $this->uid AND type = '$type'";
              $this->error = empty(Query::query($query));
          }else{
              $data = [];
              $data['comment_like_count'] = 1;
              $data['comment_like_uid'] = $this->uid;
              $data['cid'] = $this->cid;
              $data['type'] = $type;
              $this->error = empty(Insertion::insertRow('stream_comments_likes',$data));
          }
      }
      return $this;
  }

    public function saveDislikes(): Comments
    {
        $type = $this->types($this->type);
        if($type === "comments"){
            $query = "SELECT * FROM stream_comments_dislikes WHERE cid = $this->cid AND comment_dislike_uid = $this->uid AND type = '$type'";
            $data = Query::query($query);

            if(!empty($data)){
                $query = "DELETE FROM stream_comments_dislikes WHERE cid = $this->cid AND comment_dislike_uid = $this->uid AND type = '$type'";
                $this->error = empty(Query::query($query));
            }else{
                $data = [];
                $data['comment_dislike_count'] = 1;
                $data['comment_dislike_uid'] = $this->uid;
                $data['cid'] = $this->cid;
                $data['type'] = $type;
                $this->error = empty(Insertion::insertRow('stream_comments_dislikes',$data));
            }
        }
        return $this;
    }

  public function schema(): void
  {
      (new MysqlDynamicTables())->resolver(
          Database::database(),
          ['cid', 'comment_body', 'comment_uid','bundle', 'entity_id'],
          [
              'cid'=>['int(11)', 'auto_increment', 'primary key'],
              'comment_body'=>['text', 'null'],
              'comment_uid'=>['int(11)'],
              'bundle'=>['varchar(20)'],
              'entity_id'=>['int(11)']
          ],
          'stream_comments',
          false
      );

      (new MysqlDynamicTables())->resolver(
          Database::database(),
          ['rid', 'comment_reply_body', 'comment_reply_uid', 'cid'],
          [
              'rid'=>['int(11)', 'auto_increment', 'primary key'],
              'comment_reply_body'=>['text', 'null'],
              'comment_reply_uid'=>['int(11)'],
              'cid'=>['int(11)']
          ],
          'stream_comments_reply',
          false
      );

      (new MysqlDynamicTables())->resolver(
          Database::database(),
          ['lid', 'comment_like_count', 'comment_like_uid', 'cid', 'rid', 'type'],
          [
              'lid'=>['int(11)', 'auto_increment', 'primary key'],
              'comment_like_count'=>['int(11)'],
              'comment_like_uid'=>['int(11)'],
              'cid'=>['int(11)'],
              'rid'=>['int(11)'],
              'type'=>['varchar(100)']
          ],
          'stream_comments_likes',
          false
      );

      (new MysqlDynamicTables())->resolver(
          Database::database(),
          ['dsid', 'comment_dislike_count', 'comment_dislike_uid', 'cid', 'rid', 'type'],
          [
              'dsid'=>['int(11)', 'auto_increment', 'primary key'],
              'comment_dislike_count'=>['int(11)'],
              'comment_dislike_uid'=>['int(11)'],
              'cid'=>['int(11)'],
              'rid'=>['int(11)'],
              'type'=>['varchar(100)']
          ],
          'stream_comments_dislikes',
          false
      );
  }

  public function types(int $type): string
  {
      $types[] = "comments";
      $types[] = "replies";

      return $types[$type];
  }


  public function commentInformation(): array
  {
      $query = "SELECT * FROM stream_comments WHERE bundle = '$this->bundle' AND entity_id = $this->entityID";
      $comments = Query::query($query);

      $full = [];
      foreach ($comments as $key=>$value){
          $temp = $value;
          $temp['replies'] = $this->getReplies($value['cid']);
          $full[] = $temp;
      }
      return $full ?? $comments;
  }

  private function getReplies(mixed $cid)
  {
      return Selection::selectById("stream_comments_reply", ['cid'=>$cid]);
  }

  public function likes(): int
  {
      $type = $this->types($this->type);
      $query = "SELECT count(lid) AS total FROM stream_comments_likes WHERE cid = $this->cid AND type = '$type'";
      $result = Query::query($query);
      return $result[0]['total'] ?? 0;
  }

    public function disLikes(): int
    {
        $type = $this->types($this->type);
        $query = "SELECT count(dsid) AS total FROM stream_comments_dislikes WHERE cid = $this->cid AND type = '$type'";
        $result = Query::query($query);
        return $result[0]['total'] ?? 0;
    }

  public static function likesCount(int $cid, int $type): int
  {
      return (new Comments(cid: $cid, type: $type))->likes();
  }

  public static function disLikesCount(int $cid, int $type): int
    {
        return (new Comments(cid: $cid, type: $type))->disLikes();
    }
}