<?php
namespace Anax\FlashMessage;
class CFlashMessage
{

    private function addMessage($message, $type) {
        if(isset($_SESSION['flashmessages'])){
            $flashMessages = $_SESSION['flashmessages'];
        }
        $flashMessage = [
            'message' => $message,
            'type' => $type,
        ];
        $flashMessages[] = $flashMessage;
        $_SESSION['flashmessages'] = $flashMessages;
    }
    public function addError($message) {
        $this->addMessage($message, 'alert alert-danger');
    }
    public function addSuccess($message) {
        $this->addMessage($message, 'alert alert-success');
    }
    public function addNotice($message) {
        $this->addMessage($message, 'alert alert-info');
    }
    public function addWarning($message) {
        $this->addMessage($message, 'alert alert-warning');
    }
    private function deleteMessages() {
        $_SESSION['flashmessages'] = null;
    }
    public function getFlashMessages() {
        if(isset($_SESSION['flashmessages'])){
            $messages = $_SESSION['flashmessages'];
            $html = "";
            foreach ($messages as $message) {
                $html .= "<br> <div class='" . $message['type'] . "'>"  . $message['message'] . "<a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a></div>";
            }
            $this->deleteMessages();
            return $html;
        }
    }
}
