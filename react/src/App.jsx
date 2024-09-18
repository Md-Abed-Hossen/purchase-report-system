import React, { useState } from 'react';
import axios from 'axios';
import './App.css';

function App() {
  const [report, setReport] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const generateReport = async () => {
    setLoading(true);
    setError(null);

    try {
      const response = await axios.get('http://localhost:8000/api/generate-report');
      setReport(response.data);
    } catch (err) {
      setError('An error occurred while generating the report.');
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="App">
      <header className="App-header">
        <h1>Purchase Report</h1>
        <button
          onClick={generateReport}
          disabled={loading}
          className="generate-button"
        >
          {loading ? 'Generating...' : 'Generate Report'}
        </button>

        {error && <p className="error">{error}</p>}

        {report && (
          <div className="report-container">
            <h2>Top Purchasers</h2>
            <table>
              <thead>
                <tr>
                  <th>Product Name</th>
                  <th>Customer Name</th>
                  <th>Quantity</th>
                  <th>Price</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                {report.report.map((item, index) => (
                  <tr key={index}>
                    <td>{item.product_name}</td>
                    <td>{item.customer_name}</td>
                    <td>{item.total_quantity}</td>
                    <td>${item.price}</td>
                    <td>${item.total_amount}</td>
                  </tr>
                ))}
              </tbody>
              <tfoot>
                <tr>
                  <td colSpan="2">Gross Total:</td>
                  <td>{report.totalQuantity}</td>
                  <td>${report.totalPrice}</td>
                  <td>${report.grossTotal}</td>
                  
                </tr>
              </tfoot>
            </table>
          </div>
        )}
      </header>
    </div>
  );
}

export default App;