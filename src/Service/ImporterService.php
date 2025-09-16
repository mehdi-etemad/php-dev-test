<?php

namespace silverorange\DevTest\Service;

class ImporterService
{
    /** @var array<int, array<string, mixed>> */
    private array $jsonArray = [];
    public function __construct(private \PDO $db)
    {}

    public function import(): string
    {
        $result = null;
        //
        $path = __DIR__.'/../../data';
        if (!is_dir($path)) {
            return 'The path does not exist.';
        }
        
        $readCount = 0;
        $invalidJSONFormat = 0;
        if ($dir=opendir($path)) {
            while(($file=readdir($dir)) !== false) {
                if($file != '.' && $file != '..') {
                    $readCount++;
                    $jsonContent = file_get_contents($path.'/'.$file);
                    if($jsonContent !== false) {
                        $jsonData = json_decode($jsonContent, true);
                        if(
                            empty($jsonData['id']) ||
                            empty($jsonData['title']) ||
                            empty($jsonData['body']) ||
                            empty($jsonData['created_at']) ||
                            empty($jsonData['modified_at']) ||
                            empty($jsonData['author'])
                        )
                            $invalidJSONFormat++;
                        else
                            $this->jsonArray[$jsonData['id']] = $jsonData;
                    } else
                        $invalidJSONFormat++;
                }
            }
            closedir($dir);
        }
        $result = '<p>Read post files count = '.$readCount.'</p>';
        $result .= '<p>Valid JSON format count = '.($readCount-$invalidJSONFormat).'</p>';
        $result .= '<p>Invalid JSON format count = '.$invalidJSONFormat.'</p>';
        //
        $allPostIdArray = array_keys($this->jsonArray);
        $insertedPostIdArray = $this->checkInsertedPosts($allPostIdArray);
        $result .= '<p>Already inserted Posts: ('.count($insertedPostIdArray).')<br />'.implode('<br />', $insertedPostIdArray).'</p>';
        $newPostIdArray = array_diff($allPostIdArray,$insertedPostIdArray);
        $result .= '<p>New Posts: ('.count($newPostIdArray).')<br />'.implode('<br />', $newPostIdArray).'</p>';
        //
        $insertedSuccessfully = $this->insertPosts($newPostIdArray);
        $result .= '<p>Insert into Database Successfully count = '.$insertedSuccessfully.'</p>';
        //
        return $result;
    }

    private function checkInsertedPosts(array $postIdArray): array
    {
        $inserted = [];
        $placeholders = rtrim(str_repeat('?,', count($postIdArray)), ',');
        $stmt = $this->db->prepare('SELECT id FROM posts WHERE id IN ('.$placeholders.')');
        $stmt->execute($postIdArray);
        $result = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        foreach($result as $insertedId)
            $inserted[] = $insertedId;
        return $inserted;
    }

    private function insertPosts(array $postIdArray): int
    {
        $success = 0;
        $stmt = $this->db->prepare("INSERT INTO posts
            (id, title, body, created_at, modified_at, author)
            VALUES
            (?, ?, ?, ?, ?, ?)");
        $stmt->bindParam(1, $id);
        $stmt->bindParam(2, $title);
        $stmt->bindParam(3, $body);
        $stmt->bindParam(4, $created_at);
        $stmt->bindParam(5, $modified_at);
        $stmt->bindParam(6, $author);
        foreach($postIdArray as $postId) {
            $id = $this->jsonArray[$postId]['id'];
            $title = $this->jsonArray[$postId]['title'];
            $body = $this->jsonArray[$postId]['body'];
            $created_at = $this->jsonArray[$postId]['created_at'];
            $modified_at = $this->jsonArray[$postId]['modified_at'];
            $author = $this->jsonArray[$postId]['author'];
            if($stmt->execute())
                $success++;
        }
        return $success;
    }
}