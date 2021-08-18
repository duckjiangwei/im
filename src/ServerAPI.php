<?php

namespace DuckMan\Im;

use Curl\Curl;

class ServerAPI
{
    private $appKey;
    private $secret;
    private $curl;
    private $serverApiUrl;

    public function __construct()
    {
        $this->appKey = config('im.app_key');
        $this->secret = config('im.secret');
        $this->serverApiUrl = config('im.server_api_url');
        $this->curl = new Curl();
        $this->curl->setTimeout(5);
    }

    /**
     * 设置超时时间
     *
     * @param $seconds 秒
     */
    public function setTimeOut($seconds)
    {
        $this->curl->setTimeout($seconds);
    }

    /**
     * 注册
     *
     * @param $uid 唯一标识
     * @param string $nickname 昵称
     * @param string $avatar 头像链接1
     * @return string
     */
    public function register($uid, $nickname = '', $avatar = '')
    {
        $this->setHeader();

        $data = [
            'uid'      => $uid,
            'nickname' => $nickname,
            'avatar'   => $avatar,
        ];

        $this->curl->post($this->serverApiUrl . 'users/register', $data);

        return $this->curl->response;
    }

    /**
     * 发消息
     *
     * @param $token token
     * @param $type 消息类型
     * @param $targetUid 接收者id
     * @param $content 消息体
     * @param $push 是否推送:1是0否
     * @return string
     */
    public function send($token, $type, $targetUid, $content, $push = 0)
    {
        $data = [
            'token'      => $token,
            'type'       => $type,
            'target_uid' => $targetUid,
            'content'    => $content,
            'push'       => $push,
        ];

        $this->curl->post($this->serverApiUrl . 'messages/send', $data);

        return $this->curl->response;
    }

    /**
     * 通过应用端发消息
     *
     * @param $fromUid 发送者
     * @param $type 消息类型
     * @param $targetUid 接收者
     * @param $content 消息体
     * @param int $push 是否推送 0否1是
     * @return mixed
     */
    public function sendByApps($fromUid, $type, $targetUid, $content, $push = 0)
    {
        $this->setHeader();

        $data = [
            'from_uid'   => $fromUid,
            'type'       => $type,
            'target_uid' => $targetUid,
            'content'    => $content,
            'push'       => $push,
        ];

        $this->curl->post($this->serverApiUrl . 'messages/sendByApps', $data);

        return $this->curl->response;
    }

    /**
     * 消息到达回调
     *
     * @param $token token
     * @param $msgId 消息id
     * @return string
     */
    public function messageArrival($token, $msgId)
    {
        $data = [
            'token'  => $token,
            'msg_id' => $msgId,
        ];

        $this->curl->post($this->serverApiUrl . 'messages/messageArrival', $data);

        return $this->curl->response;
    }

    /**
     * 历史消息
     *
     * @param $token
     * @param $linkUser 聊天对象uid
     * @param int $nodeMarker 消息节点
     * @param int $limit 每次拉取的行数
     * @return string
     */
    public function getHistoricalMessage($token, $linkUser, $nodeMarker = 0, $limit = 10)
    {
        $data = [
            'token'       => $token,
            'link_user'   => $linkUser,
            'node_marker' => $nodeMarker,
            'limit'       => $limit,
        ];

        $this->curl->post($this->serverApiUrl . 'messages/getHistoricalMessage', $data);

        return $this->curl->response;
    }

    /**
     * 上线广播
     * @param $token
     * @return string
     */
    public function onlineNotice($token)
    {
        $data = [
            'token' => $token,
        ];

        $this->curl->post($this->serverApiUrl . 'messages/onlineNotice', $data);

        return $this->curl->response;
    }

    /**
     * 消息设置已读
     *
     * @param $token
     * @param $targetUid
     * @return null
     */
    public function readMsg($token, $targetUid)
    {
        $data = [
            'token'      => $token,
            'target_uid' => $targetUid,
        ];
        $this->curl->post($this->serverApiUrl . 'chat/readMsg', $data);

        return $this->curl->response;
    }

    /**
     * 聊天界面联系人列表
     *
     * @param $token
     * @return null
     */
    public function users($token)
    {
        $data = [
            'token' => $token,
        ];
        $this->curl->get($this->serverApiUrl . 'chat/users', $data);

        return $this->curl->response;
    }

    /**
     * 获取联系人列表在线状态
     *
     * @param $token
     * @param $uids
     * @return null
     */
    public function onlineStatus($token, $uids)
    {
        $data = [
            'token' => $token,
            'uids'  => $uids,
        ];
        $this->curl->get($this->serverApiUrl . 'chat/onlineStatus', $data);

        return $this->curl->response;
    }

    /**
     * 总消息条数
     *
     * @param $token
     * @return null
     */
    public function getAllNewMessage($token)
    {
        $data = [
            'token' => $token,
        ];
        $this->curl->get($this->serverApiUrl . 'chat/getAllNewMessage', $data);

        return $this->curl->response;
    }

    /**
     * 总消息条数
     *
     * @param $token
     * @param $days 保留的天数
     * @return null
     */
    public function lastMsgClear($token, $days)
    {
        $data = [
            'token' => $token,
            'days'  => $days,
        ];
        $this->curl->post($this->serverApiUrl . 'chat/lastMsgClear', $data);

        return $this->curl->response;
    }

    /**
     *  删除欢迎语
     *
     * @param $token
     * @return null
     */
    public function del($token)
    {
        $data = [
            'token' => $token,
        ];
        $this->curl->post($this->serverApiUrl . 'welcomes/del', $data);

        return $this->curl->response;
    }

    /**
     * 获取我的欢迎语
     *
     * @param $token
     * @return null
     */
    public function myContent($token)
    {
        $data = [
            'token' => $token,
        ];
        $this->curl->get($this->serverApiUrl . 'welcomes/myContent', $data);

        return $this->curl->response;
    }

    /**
     * 获取用户的欢迎语
     *
     * @param $token
     * @param $uid
     * @return null
     */
    public function content($token, $uid)
    {
        $data = [
            'token' => $token,
            'uid'   => $uid,
        ];
        $this->curl->get($this->serverApiUrl . 'welcomes/content', $data);

        return $this->curl->response;
    }

    /**
     * 设置欢迎语
     *
     * @param $token
     * @param $content
     * @return null
     */
    public function set($token, $content)
    {
        $data = [
            'token'   => $token,
            'content' => $content,
        ];
        $this->curl->post($this->serverApiUrl . 'welcomes/set', $data);

        return $this->curl->response;
    }


    /**
     * 融云消息同步到IM
     *
     * @param $fromUId
     * @param $targetUid
     * @param $type
     * @param $content
     * @param $createdAt
     * @param $appId
     * @return null
     */
    public function messageTransfer($fromUId, $targetUid, $type, $content, $createdAt)
    {
        $this->setHeader();

        $data = [
            'from_uid'   => $fromUId,
            'target_uid' => $targetUid,
            'type'       => $type,
            'content'    => $content,
            'created_at' => $createdAt,
        ];
        $this->curl->post($this->serverApiUrl . 'messages/messageTransfer', $data);

        return $this->curl->response;
    }

    /**
     * 获取双方信息
     *
     * @param $token
     * @param $targetUid
     * @return null
     */
    public function getConversationInfo($token, $targetUid)
    {
        $data = [
            'token'      => $token,
            'target_uid' => $targetUid,
        ];
        $this->curl->get($this->serverApiUrl . 'chat/getConversationInfo', $data);

        return $this->curl->response;
    }

    public function messageSynchronization($token, $fromUid, $limit)
    {
        $data = [
            'token'    => $token,
            'from_uid' => $fromUid,
            'limit'    => $limit,
        ];
        $this->curl->post($this->serverApiUrl . 'messages/messageSynchronization', $data);

        return $this->curl->response;
    }

    /**
     * 获取用户在线状态
     *
     * @param $appKey
     * @param $appSecret
     * @param $uids  json ["11","22"]
     * @return null
     */
    public function onlineStatusByUids($appKey, $appSecret, $uids)
    {
        $data = [
            'app_key'    => $appKey,
            'app_secret' => $appSecret,
            'uids'       => $uids,
        ];
        $this->curl->get($this->serverApiUrl . 'chat/onlineStatusByUids', $data);

        return $this->curl->response;
    }

    private function setHeader()
    {
        $nonce = mt_rand();
        $timeStamp = time();
        $sign = sha1($this->secret . $nonce . $timeStamp);

        $this->curl->setHeader('nonce', $nonce);
        $this->curl->setHeader('time-stamp', $timeStamp);
        $this->curl->setHeader('sign', $sign);
        $this->curl->setHeader('app-key', $this->appKey);
    }

    /**
     * 拉黑
     *
     * @param $uid 被拉黑用戶唯一标识
     * @return null
     */
    public function block($uid)
    {
        $this->setHeader();

        $data = [
            'uid' => $uid,
        ];

        $this->curl->post($this->serverApiUrl . 'users/block', $data);

        return $this->curl->response;
    }

    /**
     * 解除拉黑
     *
     * @param $uid 被拉黑用戶唯一标识
     * @return null
     */
    public function unBlock($uid)
    {
        $this->setHeader();

        $data = [
            'uid' => $uid,
        ];

        $this->curl->post($this->serverApiUrl . 'users/unBlock', $data);

        return $this->curl->response;
    }

    /**
     * 消息數據列表
     *
     * @param $startTime 開始時間
     * @param $endTime 結束時間
     * @param $conversation 會話標識
     * @param $page 頁碼
     * @param $size 每頁條數
     * @param $needCount 是否需要返回總條數
     * @return null
     */
    public function messages($startTime, $endTime, $conversation, $page, $size, $needCount)
    {
        $this->setHeader();

        $data = [
            'start_time'   => $startTime,
            'end_time'     => $endTime,
            'conversation' => $conversation,
            'page'         => $page,
            'size'         => $size,
            'need_count'   => $needCount,
        ];

        $this->curl->get($this->serverApiUrl . 'data/messages', $data);

        return $this->curl->response;
    }

    /**
     * 消息黑名单关键词
     *
     * @return mixed
     */
    public function msgWordsBlacklist()
    {
        $this->setHeader();

        $this->curl->get($this->serverApiUrl . 'config/msgWordsBlacklist');

        return $this->curl->response;

    }

    /**
     * 添加,编辑消息黑名单关键词
     *
     * @param $configValue
     * @return mixed
     */
    public function msgWordsBlacklistEdit($configValue)
    {
        $this->setHeader();

        $data = [
            'config_value' => $configValue,
        ];

        $this->curl->post($this->serverApiUrl . 'config/msgWordsBlacklist/edit', $data);

        return $this->curl->response;

    }

    /**
     * 删除配置项
     *
     * @param $configKey
     * @return mixed
     */
    public function deleteConfig($configKey)
    {
        $this->setHeader();

        $data = [
            'config_key' => $configKey,
        ];

        $this->curl->delete($this->serverApiUrl . 'config/delete', $data);

        return $this->curl->response;

    }
}