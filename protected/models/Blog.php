<?php
// protected/models/Blog.php
class Blog extends CActiveRecord
{
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_ARCHIVED = 'archived';
    
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    public function tableName()
    {
        return 'blogs';
    }
    
    public function rules()
    {
        return array(
            // Required fields
            array('title, content, userId', 'required'),
            
            // Length validations
            array('title', 'length', 'max' => 255),
            array('excerpt', 'length', 'max' => 500),
            array('featuredImage', 'length', 'max' => 255),
            array('userId', 'length', 'max' => 26),
            
            // Status validation
            array('status', 'in', 'range' => array(self::STATUS_DRAFT, self::STATUS_PUBLISHED, self::STATUS_ARCHIVED)),
            array('status', 'default', 'value' => self::STATUS_DRAFT),
            
            // Published date
            array('publishedAt', 'safe'),
            
            // Search
            array('id, title, content, excerpt, status, featuredImage, userId, createdAt, updatedAt, publishedAt', 'safe', 'on' => 'search'),
        );
    }
    
    public function relations()
    {
        return array(
            'author' => array(self::BELONGS_TO, 'User', 'userId'),
        );
    }
    
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'excerpt' => 'Excerpt',
            'status' => 'Status',
            'featuredImage' => 'Featured Image',
            'userId' => 'Author',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            'publishedAt' => 'Published At',
        );
    }
    
    public function beforeSave()
    {
        if ($this->isNewRecord) {
            // Set current user as author if not set
            if (empty($this->userId) && !Yii::app()->user->isGuest) {
                $this->userId = Yii::app()->user->id;
            }
        }
        
        // Auto-set publishedAt when status changes to published
        if ($this->status == self::STATUS_PUBLISHED && empty($this->publishedAt)) {
            $this->publishedAt = new CDbExpression('NOW()');
        }
        
        return parent::beforeSave();
    }
    
    public function search()
    {
        $criteria = new CDbCriteria;
        
        $criteria->compare('id', $this->id);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('userId', $this->userId, true);
        
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'createdAt DESC',
            ),
        ));
    }
    
    // Get only published blogs
    public function published()
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => "status = :status",
            'params' => array(':status' => self::STATUS_PUBLISHED),
            'order' => 'publishedAt DESC',
        ));
        return $this;
    }
    
    // Get blogs by user
    public function byUser($userId)
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => "userId = :userId",
            'params' => array(':userId' => $userId),
        ));
        return $this;
    }
    
    // Check if current user is author
    public function isAuthor()
    {
        if (Yii::app()->user->isGuest) return false;
        return $this->userId == Yii::app()->user->id;
    }
    
    // Check if current user is admin
    // Check if current user is admin
    public static function isAdminUser()
    {
        return !Yii::app()->user->isGuest && Yii::app()->user->getState('role') === 'admin'; // FIXED
    }
    
    // Can user edit this blog?
    public function canEdit()
    {
        return $this->isAuthor() || self::isAdminUser();
    }
    
    // Can user delete this blog?
    public function canDelete()
    {
        return self::isAdminUser(); // Only admin can delete
    }
    
    // Get status options
    public function getStatusOptions()
    {
        return array(
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PUBLISHED => 'Published',
            self::STATUS_ARCHIVED => 'Archived',
        );
    }
    
    // Get status label with CSS class
    public function getStatusLabel()
    {
        $statusLabels = array(
            self::STATUS_DRAFT => array('label' => 'Draft', 'class' => 'label label-default'),
            self::STATUS_PUBLISHED => array('label' => 'Published', 'class' => 'label label-success'),
            self::STATUS_ARCHIVED => array('label' => 'Archived', 'class' => 'label label-warning'),
        );
        
        return isset($statusLabels[$this->status]) ? $statusLabels[$this->status] : 
               array('label' => 'Unknown', 'class' => 'label label-default');
    }
}
?>