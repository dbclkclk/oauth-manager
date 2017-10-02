var React = require('react');
var ReactRouter = require('react-router-dom');
var Router = ReactRouter.BrowserRouter;
var Route = ReactRouter.Route;
var Index = require('../pages/Index');
var SideBar = require('../component/SideBar');


class App extends React.Component {

    render () {
        return (
            <Router>
                <div className="container">
                    <SideBar/>
                    <Route path="/" component={Index} />
                </div>
            </Router>
        );
    };
}

module.exports = App;