<?php

namespace Application\Admin\Controller;

use \Application\Admin\Model\Statistics as Stat;
use \Venus\Request as Request;
use \Venus\Admin as Admin;

class Statistics extends Base {
    public function index() {
        if (!Admin::logged()) {
            header("location: /admin/login");
        }
        $stat = new Stat();
        $this->view->numberOfHiddenCategories = $stat->getNumberOfHiddenCategories();
        $this->view->numberOfHiddenQuizzes = $stat->getNumberOfHiddenQuizzes();
        $this->view->numberOfSystemQuiz = $stat->getNumberOfSystemQuiz();
        $this->view->numberOfContributedQuiz = $stat->getNumberOfContributedQuiz();
        $this->view->numberOfBlockedUsers = $stat->getNumberOfBlockedUsers();
        $this->view->numberOfQuestions = $stat->getNumberOfQuestions();
        $this->view->attemptTimeTwoWeeks = $stat->getAttemptTimeForTwoWeeks();

        $this->view->numberOfUsersToday = $stat->getNumberOfUsers('day');
        $this->view->numberOfUsersThisMonth = $stat->getNumberOfUsers('month');
        $this->view->numberOfUsersThisYear = $stat->getNumberOfUsers('year');
        $this->view->numberOfUsers = $stat->getNumberOfUsers();

        $this->view->numberOfGuestToday = $stat->getNumberOfGuest('day');
        $this->view->numberOfGuestThisMonth = $stat->getNumberOfGuest('month');
        $this->view->numberOfGuestThisYear = $stat->getNumberOfGuest('year');
        $this->view->numberOfGuest = $stat->getNumberOfGuest();

        $this->view->numberOfQuizToday = $stat->getNumberOfQuiz('day');
        $this->view->numberOfQuizThisMonth = $stat->getNumberOfQuiz('month');
        $this->view->numberOfQuizThisYear = $stat->getNumberOfQuiz('year');
        $this->view->numberOfQuiz = $stat->getNumberOfQuiz();

        $this->view->numberOfAttemptsToday = $stat->getNumberOfAttempts('day');
        $this->view->numberOfAttemptsThisMonth = $stat->getNumberOfAttempts('month');
        $this->view->numberOfAttemptsThisYear = $stat->getNumberOfAttempts('year');
        $this->view->numberOfAttempts = $stat->getNumberOfAttempts();

        $this->view->popularQuiz = $stat->getPopularQuiz();
        $this->view->dataForPie = $stat->getDataForPie();

        $this->view->setTitle('Cài đặt trang web');
        $this->view->render('index');
    }
}