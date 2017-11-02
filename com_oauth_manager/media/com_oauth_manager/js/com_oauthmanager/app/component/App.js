var React = require('react');
var ReactRouter = require('react-router-dom');
var Router = ReactRouter.BrowserRouter;
var Route = ReactRouter.Route;
var Index = require('../pages/Index');
var SideBar = require('../component/SideBar');
var styles = require('../scss/components/App.scss');

class App extends React.Component {

    render () {
        return (
            <Router>
                <div styleName="defaultgrid">
                    <div>
                        <SideBar />
                        <div>
                            <Route path="/" component={Index} />
                        </div>
                    </div>
                </div>
            </Router>
        );
    };
}

module.exports = App;