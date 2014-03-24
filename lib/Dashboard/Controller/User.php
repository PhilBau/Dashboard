<?php

class Dashboard_Controller_User extends Zikula_AbstractController
{
    public function view()
    {
        if (!SecurityUtil::checkPermission('Dashboard::', '::', ACCESS_READ)) {
            return LogUtil::registerPermissionError();
        }

        $uid = $this->request->getSession()->get('uid');

        $helper = new Dashboard_Helper_WidgetHelper($this->entityManager);
        $dashboard = $helper->getUserWidgets($uid);

        $this->view->assign('userWidgets', $dashboard);

        $widgets = $helper->getRegisteredWidgets($uid);

        $this->view->assign('widgets', $widgets);
        $checkbox = (int) $this->request->getSession()->get('dashboard/available_widget_checkbox', false);
        $this->view->assign('available_checkbox', $checkbox);

        return $this->view->fetch('User/view.html.tpl');
    }

    public function addWidget()
    {
        $this->checkCsrfToken();

        if (!SecurityUtil::checkPermission('Dashboard::', '::', ACCESS_READ)) {
            return LogUtil::registerPermissionError();
        }

        $widgetId = $this->request->request->get('id', null);
        if (null === $widgetId) {
            throw new \InvalidArgumentException($this->__(sprintf('%s not found', $widgetId)));
        }

        $widget = $this->entityManager->getRepository('Dashboard_Entity_Widget')
            ->findOneBy(array('id' => $widgetId));

        if (!$widget) {
            throw new Exception(sprintf('Widget id %s not found', $widgetId));
        }

        $class = $widget->getClass();
        /* @var Dashboard_AbstractWidget $widget */
        $widget = new $class();
        if (false === ModUtil::available($widget->getModule())) {
            throw new Exception($this->__(sprintf('%s not available (disabled or not installed', $widget->getModule())));
        }

        $uid = $this->request->getSession()->get('uid');

        // if this widget is a default one and the user is admin, add it to all users
        if (isset($_POST['set_default_widget'])) {
            $defWidget = $this->request->getPost()->get('set_default_widget', null);
            if (null === $defWidget) {
                throw new \InvalidArgumentException($this->__(sprintf('%s not found', $defWidget)));
            }

            $widget->setDefWidget(1);
            Dashboard_Util::addUserWidget($uid, $widget, 1);
        }
        // add it only to the current user
        else {
            Dashboard_Util::addUserWidget($uid, $widget, 0);
        }

        return $this->redirect(ModUtil::url('Dashboard', 'user', 'view'));
    }

    public function removeWidget()
    {
        $this->checkCsrfToken();

        if (!SecurityUtil::checkPermission('Dashboard::', '::', ACCESS_READ)) {
            return LogUtil::registerPermissionError();
        }

        $id = $this->request->request->get('id', null);
        if (null === $id) {
            throw new \InvalidArgumentException($this->__('id not specified'));
        }

        Dashboard_Util::removeUserWidget($id);

        return $this->redirect(ModUtil::url('Dashboard', 'user', 'view'));
    }

    public function updateParameters()
    {
        $this->checkCsrfToken();

        if (!SecurityUtil::checkPermission('Dashboard::', '::', ACCESS_READ)) {
            return LogUtil::registerPermissionError();
        }

        // Get return page
        $returnPage = urldecode($this->request->request->get('returnpage', ModUtil::url('Dashboard', 'user', 'view')));

        $id = $this->request->request->get('id', null);
        if (null === $id) {
            throw new \InvalidArgumentException($this->__('id not specified'));
        }

        $index = '1';
        $parameters = array();
        $paramName = '';

        // Serialize the widget parameters
        while(true) {
            $paramName = $this->request->getPost()->get('paramname'.$index, null);
            if (null === $paramName) {
                break;    
            }

            // Get the parameters
            $param = $this->request->getPost()->get('param'.$index, null);
            if (null === $param) {
                throw new \InvalidArgumentException($this->__('param not specified'));
            }

            $parameters[$paramName] = $param;
            ++$index;
        }

        $uid = $this->request->getSession()->get('uid');
        Dashboard_Util::updateUserParameters($id, $parameters);

        return $this->redirect($returnPage);
    }

}
